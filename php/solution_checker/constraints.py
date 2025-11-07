import math
from constants import *
from errors import InstanceError

"""
This file checks all solution constraints as defined in the kiro2025.pdf.

It performs 5 main checks:
1.  Customer Partitioning: Each customer is in exactly one route.
2.  Vehicle Capacity: Route weight does not exceed vehicle capacity.
3.  Vehicle Type: Small street / fresh product rules are followed.
4.  Time Windows: Simulates each route to ensure deliveries are
    made within [t_min, t_max].
5.  Data Integrity: Catches invalid customer or vehicle IDs.
"""

# --- Internal Helper Functions ---

def _get_travel_duration(vehicle, customer_i, customer_j, departure_time_t, network_Manhattan):
    """
    Calculates the time-dependent travel duration τ_s,f(i, j | t).
    This implements the formulas from section 2 of the PDF.
    
    Args:
        vehicle (dict): The vehicle object (from instance["vehicles"]).
        customer_i (dict): The customer object for the source.
        customer_j (dict): The customer object for the destination.
        departure_time_t (float): The departure time from customer_i.

    Returns:
        float: The travel duration in minutes.
    """
    
    # 1. Calculate Reference Travel Time: τ_s,f(i, j)
    # τ_s,f(i,j) = δ_M(i, j)/sf + pf
    ref_time = (
        network_Manhattan[customer_i[ID]][customer_j[ID]] * vehicle[SPEED] + vehicle[PARKING_TIME]
    )

    # 2. Calculate Time-Dependent Factor: γ_s,f(t)
    # γ_s,f(t) = Σ[n=0 to 3] (α(n)cos(nωt) + β(n)sin(nωt))
    
    t = departure_time_t
    gamma = vehicle["fourier_cos_0"]  # n=0 (sin(0)=0)

    gamma += (
        vehicle["fourier_cos_1"] * math.cos(1 * OMEGA * t) +
        vehicle["fourier_sin_1"] * math.sin(1 * OMEGA * t)
    )
    gamma += (
        vehicle["fourier_cos_2"] * math.cos(2 * OMEGA * t) +
        vehicle["fourier_sin_2"] * math.sin(2 * OMEGA * t)
    )
    gamma += (
        vehicle["fourier_cos_3"] * math.cos(3 * OMEGA * t) +
        vehicle["fourier_sin_3"] * math.sin(3 * OMEGA * t)
    )

    # 3. Return final duration
    # τ_s,f(i,j|t) = τ_s,f(i,j) * γ_s,f(t)
    return ref_time * gamma


def _extract_customer_sequence(route):
    """
    Extracts the ordered customer sequence from a route dictionary.
    (Internal copy from cost.py to avoid circular dependencies).
    """
    sequence = []
    idx = 1
    while f'customer_{idx}' in route:
        customer_id = route[f'customer_{idx}']
        if customer_id is not None:
            sequence.append(customer_id)
        else:
            # Reaches the end of this route's sequence (e.g., customer_3 is None)
            break
        idx += 1
    return sequence

# --- Main Constraint Check Function ---

def check_constraints(instance, solution):
    """
    Verifies that the solution satisfies all constraints from the PDF.
    Raises InstanceError if any constraint is violated.
    
    Args:
        instance (dict): The loaded instance data.
        solution (list): The loaded solution data (list of routes).
    """
    
    errors = []
    
    # --- 1. Build lookup maps for fast access ---
    
    # Customer map: {id: customer_dict}
    try:
        customer_map = {c[ID]: c for c in instance["customers"]}
    except KeyError:
        raise InstanceError(["Invalid customers.csv: 'id' column not found."])

    # Vehicle map: {(small, refrigerated): vehicle_dict}
    # Note: We cast vehicle 'small'/'refrigerated' (floats) to int
    # to match the solution file's 'small'/'refrigerated' (ints).
    try:
        vehicle_map = {
            (v['family']): v 
            for v in instance["vehicles"]
        }
    except KeyError:
        raise InstanceError(["Invalid vehicles.csv: 'family' not found"])

    # --- 2. Check Customer Partitioning (Constraint 1) ---
    
    # Get all customer IDs that must be served (all except depot)
    all_customer_ids = {c[ID] for c in instance["customers"] if c[ID] != DEPOT_ID}
    served_customer_ids = []

    # --- 3. Check each route for constraints (2, 3) ---
    
    for route_idx, route in enumerate(solution):
        
        # Get the customer sequence for this route
        customer_sequence = _extract_customer_sequence(route)
        
        if not customer_sequence:
            continue  # Empty route, only pays rental (checked in cost.py)

        # --- Get Route Vehicle ---
        try:
            route_vehicle = vehicle_map[route[FAMILY]]
        except KeyError:
            errors.append(
                f"Route {route_idx}: Invalid vehicle family specified "
                f"(family={route[FAMILY]})."
            )
            # Cannot check other constraints for this route, skip to next
            continue 

        total_weight = 0
        current_time = DEPARTURE_TIME  # All trucks depart at 5:00 AM
        current_customer = customer_map[DEPOT_ID] # Start at depot

        # --- Simulate the route, customer by customer ---
        for customer_id in customer_sequence:
            
            if customer_id not in customer_map:
                errors.append(f"Route {route_idx}: Contains invalid customer ID {customer_id}.")
                continue
            
            next_customer = customer_map[customer_id]
            served_customer_ids.append(customer_id)

            # --- Check Time Constraints (3a, 3b, 3c, 3d) ---
            
            # Calculate travel time from current_customer to next_customer
            travel_duration = _get_travel_duration(
                route_vehicle, current_customer, next_customer, current_time, instance["network_Manhattan"]
            )
            
            # Constraint 3b/3c: Arrival time at customer
            arrival_time = current_time + travel_duration
            
            # Constraint 3d: Check arrival against time window [t_min, t_max]
            if arrival_time > next_customer[WINDOW_END]:
                errors.append(
                    f"Route {route_idx}: Late arrival at customer {customer_id}. "
                    f"Arrived at {arrival_time:.2f}, window ends at {next_customer[WINDOW_END]}."
                )

            # Constraint 3a: Calculate departure time
            # Wait if arriving early, then perform delivery
            delivery_start_time = max(arrival_time, next_customer[WINDOW_START])
            departure_time = delivery_start_time + next_customer[DELIVERY_DURATION]

            # Update state for next loop iteration
            current_time = departure_time
            current_customer = next_customer
            
            # Accumulate weight for capacity check
            total_weight += next_customer[ORDER_WEIGHT]

        # --- Check Vehicle Capacity Constraint (2) ---
        if total_weight > route_vehicle[MAX_CAPACITY]:
            errors.append(
                f"Route {route_idx}: Exceeds capacity. "
                f"Total weight {total_weight} > max capacity {route_vehicle[MAX_CAPACITY]}."
            )

    # --- 4. Final Customer Partitioning Check (Constraint 1) ---
    
    served_set = set(served_customer_ids)
    
    # Check for duplicate visits
    if len(served_customer_ids) != len(served_set):
        # Find duplicates for a more helpful error
        seen = set()
        dupes = [x for x in served_customer_ids if x in seen or seen.add(x)]
        errors.append(f"Customer(s) visited more than once: {set(dupes)}.")
        
    # Check for missing or extra customers
    if served_set != all_customer_ids:
        missing = all_customer_ids - served_set
        if missing:
            errors.append(f"Missing customers (not served): {missing}.")
            
        extra = served_set - all_customer_ids
        if extra:
            errors.append(f"Invalid customers served (not in instance): {extra}.")

    # --- 5. Raise all collected errors ---
    if errors:
        raise InstanceError(errors)

    # If no errors were found, pass
    pass
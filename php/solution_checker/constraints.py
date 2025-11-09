import math
from constants import *
from errors import InstanceError

"""
This file checks all solution constraints as defined in the main.pdf
and interpreted by the Julia reference evaluator.
"""

# --- Internal Helper Functions ---

def _get_travel_duration(vehicle, customer_i_id, customer_j_id, departure_time_t, network_Manhattan):
    """
    Calculates the time-dependent travel duration τ_f(i, j | t).
    This implements the formulas from main.pdf [cite: 45, 47-52]
    as modified by the Julia evaluator eval.jl .
    
    Args:
        vehicle (dict): The vehicle object.
        customer_i_id (int): The ID (and index) of the source customer.
        customer_j_id (int): The ID (and index) of the destination customer.
        departure_time_t (float): The departure time from customer_i (in seconds).
        network_Manhattan (list): The 2D distance matrix.

    Returns:
        float: The travel duration in seconds.
    """
    
    # 1. Calculate Reference Travel Time: τ(i, j)
    #    τ(i,j) = δ_M(i, j) / s_f
    #
    #    NOTE: We match the Julia eval.jl which omits parking_time (p_f).
    #    The old Python code and PDF [cite: 50] included it, but the Julia ref does not.
    manhattan_distance = network_Manhattan[customer_i_id][customer_j_id]
    ref_time = manhattan_distance / vehicle[SPEED]

    # 2. Calculate Time-Dependent Factor: γ_f(t)
    #    γ_f(t) = Σ[n=0 to 3] (α(n)cos(nωt) + β(n)sin(nωt))
    #
    #    NOTE: We use OMEGA based on T=1440 (from Julia constants.jl )
    #    and 't' in seconds (from Julia eval.jl ),
    #    to faithfully reproduce the Julia implementation.
    
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
    # τ_f(i,j|t) = τ(i,j) * γ_f(t) [cite: 45]
    return ref_time * gamma


def _extract_customer_sequence(route):
    """
    Extracts the ordered customer sequence from a route dictionary.
    (This is the correct version from cost.py)
    """
    sequence = []
    idx = 1
    # As per main.pdf , headers are customer_1, customer_2, ...
    while f'customer_{idx}' in route:
        customer_id = route[f'customer_{idx}']
        if customer_id is not None:
            sequence.append(customer_id)
        else:
            # Reaches the end of this route's sequence
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
    try:
        # Customer map: {id: customer_dict}
        customer_map = {c[ID]: c for c in instance["customers"]}
    except KeyError:
        raise InstanceError(["Invalid instance file: 'id' column not found."])

    try:
        # Vehicle map: {family_id: vehicle_dict}
        vehicle_map = {
            v['family']: v 
            for v in instance["vehicles"]
        }
    except KeyError:
        raise InstanceError(["Invalid vehicles.csv: 'family' column not found"])

    # --- 2. Check Customer Partitioning (Constraint 1) ---
    
    # Get all customer IDs that must be served (all except depot)
    all_customer_ids = {c[ID] for c in instance["customers"] if c[ID] != DEPOT_ID}
    served_customer_ids = []
    
    # Tolerance for floating point time comparisons (from eval.jl )
    TIME_TOLERANCE = 1e-5

    # --- 3. Check each route for constraints (2, 3) ---
    
    for route_idx, route in enumerate(solution):
        
        # Get the customer sequence for this route
        customer_sequence = _extract_customer_sequence(route)
        
        if not customer_sequence:
            continue  # Empty route is valid (just pays rental)

        # --- Get Route Vehicle ---
        try:
            # route[FAMILY] uses FAMILY="family" from constants.py
            route_vehicle = vehicle_map[route[FAMILY]]
        except KeyError:
            errors.append(
                f"Route {route_idx+1}: Invalid vehicle family specified "
                f"(family={route.get(FAMILY)})."
            )
            # Cannot check other constraints for this route, skip to next
            continue 

        total_weight = 0
        # DEPARTURE_TIME is 0.0 (in seconds), 
        current_time = DEPARTURE_TIME  
        current_customer_id = DEPOT_ID # Start at depot

        # --- Simulate the route, customer by customer ---
        for customer_id in customer_sequence:
            
            if customer_id not in customer_map:
                errors.append(f"Route {route_idx+1}: Contains invalid customer ID {customer_id}.")
                # This customer_id is fatal for the route, skip to next route
                break 
            
            if customer_id == DEPOT_ID:
                errors.append(f"Route {route_idx+1}: Cannot visit depot (ID 0) mid-route.")
                break
            
            next_customer = customer_map[customer_id]
            served_customer_ids.append(customer_id)

            # --- Check Time Constraints (3a, 3b, 3c, 3d) ---
            
            # Calculate travel time from current_customer to next_customer
            travel_duration = _get_travel_duration(
                route_vehicle, current_customer_id, customer_id, 
                current_time, instance["network_Manhattan"]
            )
            
            # Constraint 3b/3c: Arrival time at customer [cite: 77-79]
            arrival_time = current_time + travel_duration
            
            # Constraint 3d: Check arrival against time window [t_min, t_max] [cite: 81-82]
            # 
            if arrival_time > next_customer[WINDOW_END] + TIME_TOLERANCE:
                errors.append(
                    f"Route {route_idx+1}: Late arrival at customer {customer_id}. "
                    f"Arrived at {arrival_time:.2f}, window ends at {next_customer[WINDOW_END]}."
                )

            # Constraint 3a: Calculate departure time [cite: 75-76]
            # Wait if arriving early, then perform delivery
            delivery_start_time = max(arrival_time, next_customer[WINDOW_START])
            departure_time = delivery_start_time + next_customer[DELIVERY_DURATION]

            # Update state for next loop iteration
            current_time = departure_time
            current_customer_id = customer_id
            
            # Accumulate weight for capacity check
            total_weight += next_customer[ORDER_WEIGHT]

        # --- Check Vehicle Capacity Constraint (2) ---
        if total_weight > route_vehicle[MAX_CAPACITY]:
            errors.append(
                f"Route {route_idx+1}: Exceeds capacity for family {route[FAMILY]}. "
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
            # This should be caught by the (customer_id not in customer_map)
            # check, but we include it for robustness.
            errors.append(f"Invalid customers served (not in instance): {extra}.")

    # --- 5. Raise all collected errors ---
    if errors:
        raise InstanceError(errors)

    # If no errors were found, pass
    pass
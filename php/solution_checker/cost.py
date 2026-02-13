from constants import *
from math import sqrt
from errors import InstanceError
from loader import load_csv_instance, load_csv_solution

"""
Calculates the total cost of a solution, matching the
logic from the Julia reference evaluator (eval.jl).
"""

def get_vehicle(vehicles, family):
    """
    Get vehicle from the list by 1-based family ID.
    Assumes 'vehicles' is a list of dicts, ordered by family 1, 2, ...
    This matches the Julia: instance.vehicles[route.vehicle_id] 
    """
    try:
        # 'family' is 1-based, list index is 0-based
        return vehicles[family - 1]
    except (IndexError, TypeError):
        # This error should be caught in constraints.py,
        # but we check again for safety.
        raise InstanceError([f"Vehicle not found for family={family}"])

def extract_customer_sequence(route):
    """Extract customer sequence from route dict"""
    sequence = []
    idx = 1
    # As per main.pdf , headers are customer_1, customer_2, ...
    while f'order_{idx}' in route:
        customer_id = route[f'order_{idx}']
        if customer_id is not None:
            sequence.append(customer_id)
        idx += 1
    return sequence

def rental_cost(instance, route):
    """Calculate rental cost for a route [cite: 89-90], """
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    return vehicle[RENTAL_COST]

def fuel_cost(instance, route):
    """Calculate fuel cost for a route [cite: 91-92], """
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    unit_cost = vehicle[FUEL_COST]
    
    # Sequence includes depot at start and end
    sequence = [DEPOT_ID] + extract_customer_sequence(route) + [DEPOT_ID]
    total_distance = 0
    
    for i in range(len(sequence) - 1):
        src_id, dst_id = sequence[i], sequence[i + 1]
        total_distance += instance["network_Manhattan"][src_id][dst_id]
    
    return unit_cost * total_distance

def radius_cost(instance, route):
    """
    Calculate radius penalty cost for a route.
    
    NOTE: This function implements the Julia eval.jl logic:
          cost = c_radius * (max_diameter / 2)
          
    This *differs* from the PDF [cite: 93-94] and old Python code,
    which used a squared term:
          cost = c_radius * (max_diameter / 2)^2
    """
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    unit_cost = vehicle[RADIUS_COST]
    
    sequence = extract_customer_sequence(route)
    
    # No radius cost for routes with 0 or 1 customer
    if len(sequence) < 2:
        return 0
    
    max_distance = 0.0
    for i in range(len(sequence)):
        for j in range(i + 1, len(sequence)):
            id_i = sequence[i]
            id_j = sequence[j]
            distance = instance["network_Euclidean"][id_i][id_j]
            max_distance = max(max_distance, distance)
    
    # Applying the Julia logic :
    return unit_cost * max_distance / 2.0

def route_cost(instance, route):
    """Calculate total cost for a single route"""
    # [cite: 87]
    return (rental_cost(instance, route) + 
            fuel_cost(instance, route) + 
            radius_cost(instance, route)) # Renamed from diameter_cost

def cost(instance, solution):
    """
    Calculate total cost of the solution - MAIN OBJECTIVE FUNCTION
    [cite: 87], 
    """
    total = 0
    for route in solution:
        total += route_cost(instance, route)
    
    return total
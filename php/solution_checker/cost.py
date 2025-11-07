from constants import *
from math import sqrt, cos, sin
from errors import InstanceError
from loader import load_csv_instance, load_csv_solution

"""
    Calculate total cost of the solution - MAIN OBJECTIVE FUNCTION
    instance: problem instance data loaded from load_csv_instance
    solution: routes data loaded from load_csv_solution
"""

def get_vehicle(vehicles, family):
    """Get vehicle by small and refrigerated flags"""
    for vehicle in vehicles:
        return vehicles[family]
    raise InstanceError([f"Vehicle not found for family={family}"])

def extract_customer_sequence(route):
    """Extract customer sequence from route dict"""
    sequence = []
    idx = 1
    while f'customer_{idx}' in route:
        customer_id = route[f'customer_{idx}']
        if customer_id is not None:
            sequence.append(customer_id)
        idx += 1
    return sequence

def rental_cost(instance, route):
    """Calculate rental cost for a route"""
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    return vehicle[RENTAL_COST]

def fuel_cost(instance, route):
    """Calculate fuel cost for a route"""
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    unit_cost = vehicle[FUEL_COST]
    
    sequence = [DEPOT_ID] + extract_customer_sequence(route) + [DEPOT_ID]
    total_distance = 0
    
    for i in range(len(sequence) - 1):
        src, dst = sequence[i], sequence[i + 1]
        total_distance += instance["network_Manhattan"][src][dst]
    
    return unit_cost * total_distance

def diameter_cost(instance, route):
    """Calculate diameter penalty cost for a route"""
    vehicle = get_vehicle(instance["vehicles"], route[FAMILY])
    unit_cost = vehicle[DIAMETER_COST]
    
    sequence = extract_customer_sequence(route)
    
    if len(sequence) < 2:
        return 0
    
    max_distance = 0
    for i in range(len(sequence)):
        for j in range(i + 1, len(sequence)):
            distance = instance["network_Euclidean"][sequence[i]][sequence[j]]
            max_distance = max(max_distance, distance)
    
    return unit_cost * (1/2*max_distance)**2

def route_cost(instance, route):
    """Calculate total cost for a single route"""
    return (rental_cost(instance, route) + 
            fuel_cost(instance, route) + 
            diameter_cost(instance, route))

def cost(instance, solution):
    """Calculate total cost of the solution - MAIN OBJECTIVE FUNCTION
    
    Args:
        instance: dict with keys "vehicles", "customers", "network"
                 from load_csv_instance()
        solution: list of route dicts from load_csv_solution()
    
    Returns:
        float: total cost in euro cents
    """
    total = 0
    for route in solution:
        total += route_cost(instance, route)
    
    return total

# Test
#if __name__ == "__main__":
#    instance = load_csv_instance("instances/tiny")
#    solution = load_csv_solution("instances/tiny/example_routes.csv")
#    print(total_cost(instance, solution))
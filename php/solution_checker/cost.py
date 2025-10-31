from constants import *
from math import sqrt, cos, sin
from errors import InstanceError

"""
    Calculate total cost of the solution - MAIN OBJECTIVE FUNCTION
    instance: problem instance data:
    instance = 
    {
        "vehicles": {},
        "customers": {},
        "network": {}
    }
"""
def euclidean_distance(coord1, coord2):
    return sqrt((coord1[0] - coord2[0])**2 + (coord1[1] - coord2[1])**2)

def rental_cost(instance, route):
    """Calculate rental cost for a route
    """
    vehicle_key = (route[SMALL], route[REFRIGERATED])
    return instance["vehicles"][vehicle_key][RENTAL_COST]

def fuel_cost(instance, route):
    """Calculate fuel cost for a route"""
    vehicle_key = (route[SMALL], route[REFRIGERATED])
    unit_cost = instance["vehicles"][vehicle_key][UNIT_FUEL_COST]
    
    sequence = [DEPOT_ID] + route[CUSTOMER_SEQUENCE] + [DEPOT_ID]
    total_distance = 0
    
    for i in range(len(sequence) - 1):
        src, dst = sequence[i], sequence[i + 1]
        total_distance += instance["network"][src][dst]
    
    return unit_cost * total_distance

def diameter_cost(instance, route):
    """Calculate diameter penalty cost for a route"""
    vehicle_key = (route[SMALL], route[REFRIGERATED])
    unit_cost = instance["vehicles"][vehicle_key][UNIT_DIAMETER_COST]
    
    sequence = route[CUSTOMER_SEQUENCE]
    
    if len(sequence) < 2:
        return 0
    
    max_distance = 0
    for i in range(len(sequence)):
        for j in range(i + 1, len(sequence)):
            distance = instance["network"][sequence[i]][sequence[j]]
            max_distance = max(max_distance, distance)
    
    return unit_cost * max_distance

def route_cost(instance, route):
    """Calculate total cost for a single route"""
    return (rental_cost(instance, route) + 
            fuel_cost(instance, route) + 
            diameter_cost(instance, route))

def total_cost(instance, solution):
    """ Calculate total cost of the solution - MAIN OBJECTIVE FUNCTION
        instance: problem instance data:
        instance = 
        {
            "vehicles": {},
            "customers": {},
            "network": {}
        }
    """
    total = 0
    for route in solution[ROUTES]:
        total += route_cost(instance, route)
    
    return total
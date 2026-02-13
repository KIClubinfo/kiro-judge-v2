import csv
import math
from errors import InstanceError
from constants import *

"""
This file loads and converts instance data and solution files
from their CSV format into Python data structures.
"""

def load_csv_instance(file_path, instance):
    """
    Loads all instance data (vehicles, customers) from a directory.
    
    :param file_path: Path to the instance directory (e.g., "instances/")
    :param instance: The instance file name (e.g., "instance_01")
    :return: A dictionary containing:
             - "vehicles": A list of vehicle-type dictionaries.
             - "customers": A list of customer dictionaries (incl. depot at index 0).
             - "network_Euclidean": A 2D list (adjacency matrix) for Euclidean distances.
             - "network_Manhattan": A 2D list (adjacency matrix) for Manhattan distances.
    """
    
    # --- 1. Load data from CSV files ---
    try:
        with open(f"{file_path}/vehicles.csv", 'r') as f:
            vehicles = list(csv.DictReader(f))
        # Note: The instance file is now instance.csv, not customers.csv
        with open(f"{file_path}/{instance}.csv", 'r') as f:
            customers = list(csv.DictReader(f))
    except FileNotFoundError as e:
        raise InstanceError([f"Error loading instance file: {e.filename} not found."])

    # --- 2. Process Vehicle Data ---
    for vehicle_family in vehicles:
        for key in vehicle_family:
            try:
                # 'family' is the ID, should be int
                if key == 'family':
                    vehicle_family[key] = int(float(vehicle_family[key]))
                else:
                    vehicle_family[key] = float(vehicle_family[key])
            except (ValueError, TypeError):
                raise InstanceError([f"Invalid non-numeric value '{vehicle_family[key]}' for '{key}' in vehicles.csv"])

    # --- 3. Process Customer Data ---
    INT_KEYS = ['id', 'order_weight', 'window_start', 'window_end', 'delivery_duration']
    FLOAT_KEYS = ['longitude', 'latitude']

    for customer in customers:
        for key in customer:
            if customer[key] == "" or customer[key] is None:
                customer[key] = None
            else:
                try:
                    if key in INT_KEYS:
                        customer[key] = int(float(customer[key]))
                    elif key in FLOAT_KEYS:
                        customer[key] = float(customer[key])
                except (ValueError, TypeError):
                     raise InstanceError([f"Invalid non-numeric value '{customer[key]}' for '{key}' in {instance}.csv"])
    
    # --- 4. Process Network Data (Build Adjacency Matrix) ---
    num_nodes = len(customers)
    if num_nodes == 0:
        raise InstanceError([f"{instance}.csv is empty or could not be read."])

    # Create (N x N) matrices
    network_Manhattan = [[0.0] * num_nodes for _ in range(num_nodes)]
    network_Euclidean = [[0.0] * num_nodes for _ in range(num_nodes)]
    
    try:
        # Get depot latitude in radians for the cosine term
        # The depot is ID 0, which must be the first customer in the list
        if customers[0]['id'] != 0:
            raise InstanceError(["Instance file must have depot (id=0) as the first row."])
        
        lat_depot_rad = float(customers[0]["latitude"]) * math.pi / 180.0
        
        for i in range(num_nodes):
            for j in range(i + 1, num_nodes): # Symmetric matrix

                delta_lat_deg = float(customers[i]["latitude"]) - float(customers[j]["latitude"])
                delta_lon_deg = float(customers[i]["longitude"]) - float(customers[j]["longitude"])

                # Convert degree differences to meters
                #[cite: 41], 
                delta_y_m = RHO * (delta_lat_deg * math.pi / 180.0) 
                #[cite: 41], 
                delta_x_m = RHO * math.cos(lat_depot_rad) * (delta_lon_deg * math.pi / 180.0)
                
                #[cite: 37], 
                manhattan_dist = abs(delta_x_m) + abs(delta_y_m)
                #[cite: 38], 
                euclidean_dist = math.sqrt(delta_x_m**2 + delta_y_m**2)

                network_Manhattan[i][j] = manhattan_dist
                network_Manhattan[j][i] = manhattan_dist
                network_Euclidean[i][j] = euclidean_dist
                network_Euclidean[j][i] = euclidean_dist

    except (TypeError, IndexError, KeyError):
         raise InstanceError(["Error calculating network distances. Check customer coordinates."])

    return {"vehicles": vehicles, "customers": customers, "network_Euclidean": network_Euclidean, "network_Manhattan": network_Manhattan}


def load_csv_solution(file_path):
    """
    Loads a participant's solution file (routes.csv).
    
    Expected format (as per main.pdf [cite: 132-138]):
    family,customer_1,customer_2,customer_3,...
    1,4,3,2,5
    2,1,6,,
    
    :param file_path: Path to the solution CSV file (e.g., "routes.csv")
    :return: A list of route dictionaries.
    """
    try:
        with open(f"{file_path}", 'r') as f:
            routes = list(csv.DictReader(f))
    except FileNotFoundError:
        raise InstanceError([f"Solution file not found at {file_path}"])

    for route in routes:
        for key in route:
            value = route[key]
            if value == "" or value is None:
                # Empty cells (for routes with fewer stops) become None
                route[key] = None
            else:
                # All values (family ID, customer IDs) are integers
                try:
                    route[key] = int(value)
                except ValueError:
                    raise InstanceError([f"Invalid non-integer value '{value}' for '{key}' in solution file."])
    
    return routes
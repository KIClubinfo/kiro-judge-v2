import csv
from errors import InstanceError

"""
This file loads and converts instance data and solution files
from their CSV format into Python data structures (lists and dicts).
"""

def load_csv_instance(file_path):
    """
    Loads all instance data (vehicles, customers, network) from a directory.
    
    :param file_path: Path to the instance directory (e.g., "instances/tiny")
    :return: A dictionary containing three keys:
             - "vehicles": A list of vehicle-type dictionaries.
             - "customers": A list of customer dictionaries.
             - "network": A 2D list (adjacency matrix) where network[i][j] 
                          is the distance from node i to node j.
    Example return format:
    
    vehicles = [{'small': 0.0, 'refrigerated': 0.0, 'max_capacity': 3500.0, 
                'rental_cost': 18000.0, 'unit_fuel_cost': 35.0, 'unit_diameter_cost': 20.0, 
                'longitude_diff_time': 111.67, 'latitude_diff_time': 161.67, 'bias_time': 8.33, 
                'fourier_cos_0': 1.18, 'fourier_cos_1': -0.16, 'fourier_cos_2': -0.02, 
                'fourier_cos_3': 0.03, 'fourier_sin_0': 0.0, 'fourier_sin_1': -0.12, 
                'fourier_sin_2': 0.0, 'fourier_sin_3': 0.06}
              ]
    
    customers = [{'id': 0, 'longitude': 2.3499, 'latitude': 48.7494, 'order_weight': None,
                 'window_start': None, 'window_end': None, 'delivery_duration': None, 
                 'small_street': None, 'fresh_product': None},
                {'id': 1, 'longitude': 2.3619, 'latitude': 48.85288,
                 'order_weight': 291, 'window_start': 553, 'window_end': 654, 
                 'delivery_duration': 17, 'small_street': 1, 'fresh_product': 1}
               ]

    network = [[0,     11540, 10247, 9410],
              [11540, 0,     1575,  2914], 
              [10247, 1575,  0,     1353],
              [9410,  2914,  1353,  0]]
    A 2D list of integers representing the graph with 0 on the diagonal.
    
    Returns the triplet (vehicles, customers, network) as a dictionary.
    """
    
    # --- 1. Load data from CSV files ---
    try:
        with open(f"{file_path}/vehicles.csv", 'r') as f:
            vehicles = list(csv.DictReader(f))
        with open(f"{file_path}/customers.csv", 'r') as f:
            customers = list(csv.DictReader(f))
        with open(f"{file_path}/network.csv", 'r') as f:
            network_dict = list(csv.DictReader(f))
    except FileNotFoundError as e:
        raise InstanceError([f"Error loading instance file: {e.filename} not found."])

    # --- 2. Process Vehicle Data ---
    # All vehicle parameters are numeric (costs, coefficients, etc.)
    for vehicle in vehicles:
        for key in vehicle:
            try:
                vehicle[key] = float(vehicle[key])
            except ValueError:
                raise InstanceError([f"Invalid non-numeric value '{vehicle[key]}' for '{key}' in vehicles.csv"])

    # --- 3. Process Customer Data ---
    # Define which keys should be integers vs. floats, as per kiro2025.pdf
    INT_KEYS = [
        'id', 'order_weight', 'window_start', 'window_end', 
        'delivery_duration', 'small_street', 'fresh_product'
    ]
    FLOAT_KEYS = ['longitude', 'latitude']

    for customer in customers:
        for key in customer:
            if customer[key] == "":
                customer[key] = None
            else:
                try:
                    if key in INT_KEYS:
                        # Use int(float(...)) to handle "1.0" or "1"
                        customer[key] = int(float(customer[key]))
                    elif key in FLOAT_KEYS:
                        customer[key] = float(customer[key])
                except ValueError:
                     raise InstanceError([f"Invalid non-numeric value '{customer[key]}' for '{key}' in customers.csv"])
    
    # --- 4. Process Network Data (Build Adjacency Matrix) ---
    # This is a much more robust way to build the matrix.
    num_nodes = len(customers)
    if num_nodes == 0:
        raise InstanceError(["customers.csv is empty or could not be read."])

    # Create an (N x N) matrix, where N = num_nodes
    network = [[0] * num_nodes for _ in range(num_nodes)]
    
    for edge in network_dict:
        try:
            # Get data from the CSV row
            source = int(edge["source"])
            destination = int(edge["destination"])
            distance = int(edge["distance"])
            
            # Populate the matrix
            if 0 <= source < num_nodes and 0 <= destination < num_nodes:
                network[source][destination] = distance
            else:
                print(f"Warning: network.csv edge ({source}, {destination}) is out of bounds.")
                
        except ValueError:
            raise InstanceError([f"Invalid non-integer value in network.csv: {edge}"])
    
    return {"vehicles": vehicles, "customers": customers, "network": network}


def load_csv_solution(file_path):
    """
    Loads a participant's solution file.
    
    Expected format:
    small,refrigerated,customer_1,customer_2,...
    1,1,4,3,2,5
    0,1,1,6,,
    
    :param file_path: Path to the solution CSV file (e.g., "routes.csv")
    :return: A list of route dictionaries.
             Example: [
                 {'small': 1, 'refrigerated': 1, 'customer_1': 4, 'customer_2': 3, 'customer_3': 2, 'customer_4': 5},
                 {'small': 0, 'refrigerated': 1, 'customer_1': 1, 'customer_2': 6, 'customer_3': None, 'customer_4': None}
             ]
    """
    try:
        with open(f"{file_path}", 'r') as f:
            routes = list(csv.DictReader(f))
    except FileNotFoundError:
        raise InstanceError([f"Solution file not found at {file_path}"])

    for route in routes:
        for key in route:
            value = route[key]
            if value == "":
                # Empty cells (for routes with fewer stops) become None
                route[key] = None
            else:
                # All values (small, refrigerated, customer IDs) are integers
                try:
                    route[key] = int(value)
                except ValueError:
                    raise InstanceError([f"Invalid non-integer value '{value}' for '{key}' in solution file."])
    
    return routes

# Test
# if __name__ == "__main__":
#     instance_data = load_csv_instance("instances/tiny")
#     print("--- Instance Data ---")
#     print(instance_data["vehicles"])
#     print(instance_data["customers"])
#     print(instance_data["network"])
    
#     solution_data = load_csv_solution("instances/tiny/example_routes.csv")
#     print("\n--- Solution Data ---")
#     print(solution_data)
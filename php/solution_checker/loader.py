import csv
from errors import InstanceError

"""
Ce fichier sert et de loader et de converter pour manipuler les données d'instances et de solution
"""

def load_csv_instance(file_path):
    """
    Charge les instances sous le format : 
    vehicles = [{'small': 0.0, 'refrigerated': 0.0, 'max_capacity': 3500.0, 
                'rental_cost': 18000.0, 'unit_fuel_cost': 35.0, 'unit_diameter_cost': 20.0, 
                'longitude_diff_time': 111.67, 'latitude_diff_time': 161.67, 'bias_time': 8.33, 
                'fourier_cos_0': 1.18, 'fourier_cos_1': -0.16, 'fourier_cos_2': -0.02, 
                'fourier_cos_3': 0.03, 'fourier_sin_0': 0.0, 'fourier_sin_1': -0.12, 
                'fourier_sin_2': 0.0, 'fourier_sin_3': 0.06}
              ]
    Avec des valeurs flottantes pour toutes les valeurs de vehicles
    customers = [{'id': 0.0, 'longitude': 2.3499, 'latitude': 48.7494, 'order_weight': None,
                 'window_start': None, 'window_end': None, 'delivery_duration': None, 
                 'small_street': None, 'fresh_product': None},
                {'id': 1.0, 'longitude': 2.3619, 'latitude': 48.85288,
                 'order_weight': 291.0, 'window_start': 553.0, 'window_end': 654.0, 
                 'delivery_duration': 17.0, 'small_street': 1.0, 'fresh_product': 1.0}
               ]
    Avec que des valeurs entières, et si la valeur n'existe pas on met None
    
    network = [[0,     11540, 10247, 9410],
              [11540, 0,     1575,  2914], 
              [10247, 1575,  0,     1353]
              [9410,  2914,  1353,  0]]
    
    C'est une liste de liste d'entiers qui représente bien le graphe, avec 0 sur la diagonale
    
    On renvoie enfin le triplet (vehicles, customers, network)
    """
    with open(f"{file_path}/vehicles.csv", 'r') as f:
        vehicles = list(csv.DictReader(f))
    with open(f"{file_path}/customers.csv", 'r') as f:
        customers = list(csv.DictReader(f))
    with open(f"{file_path}/network.csv", 'r') as f:
        network_dict = list(csv.DictReader(f))

    for vehicle in vehicles:
        for key in vehicle:
            vehicle[key] = float(vehicle[key])

    for customer in customers:
        for key in customer:
            if customer[key] == "":
                customer[key] = None
            else:
                customer[key] = float(customer[key])
    
    network = [[]]
    for edge in network_dict:
        if int(edge["source"]) == 0:
            network.append([])
            if(int(edge["source"]) == int(edge["destination"])-1):
                network[int(edge["source"])].append(0)

            network[int(edge["source"])].append(int(edge["distance"]))
        else:
            if(int(edge["source"]) == int(edge["destination"])-1):
                network[int(edge["source"])].append(0)
            network[int(edge["source"])].append(int(edge["distance"]))
    network[len(network[0])-1].append(0)
    
    #print(vehicles)
    #print(customers)
    #print(network)
    return {"vehicles" : vehicles, "customers": customers, "network": network}

def load_csv_solution(file_path):
    """
    routes a que des valerus entières avec des None pour les fin de routes
    routes = [
            {'small': 1, 'refrigerated': 1, 'customer_1': 1},
            {'small': 1, 'refrigerated': 1, 'customer_1': 2}
            ]
    """

    with open(f"{file_path}", 'r') as f:
        routes = list(csv.DictReader(f))
    for route in routes:
        for key in route:
            if route[key] == "":
                route[key] = None
            else:
                route[key] = int(route[key])
    #print(routes)
    return routes

# Test
# load_csv_instance("instances/tiny")
# load_csv_solution("instances/tiny/example_routes.csv") 


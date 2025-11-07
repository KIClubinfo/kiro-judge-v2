# Vehicle fields
FAMILY = "vehicle_id"
MAX_CAPACITY = "max_capacity"
RENTAL_COST = "rental_cost"
FUEL_COST = "fuel_cost"
RADIUS_COST = "radius_cost"
SPEED = "speed"
PARKING_TIME = "parking_time"
FOURIER_COS = "fourier_cos"
FOURIER_SIN = "fourier_sin"

# Customer fields
ID = "id"
LONGITUDE = "longitude"
LATITUDE = "latitude"
ORDER_WEIGHT = "order_weight"
WINDOW_START = "window_start"
WINDOW_END = "window_end"
DELIVERY_DURATION = "delivery_duration"

# Network fields
SOURCE = "source"
DESTINATION = "destination"
DISTANCE = "distance"

# Solution fields
ROUTES = "routes"
CUSTOMER_SEQUENCE = "customer_sequence"

# Constants
DEPOT_ID = 0
DEPARTURE_TIME = 300  # 5:00 AM in minutes from midnight
OMEGA = 2 * 3.14159265359 / 1440  # Daily periodicity
RHO = 6.371e6
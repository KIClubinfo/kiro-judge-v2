import math
# Vehicle fields
FAMILY = "family" 
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

# Solution fields
ROUTES = "routes"
CUSTOMER_SEQUENCE = "customer_sequence"

# Depot ID is always 0
DEPOT_ID = 0

# Earth radius in meters, from main.pdf and constants.jl 
RHO = 6.371e6

# Departure time from depot
# main.pdf  and eval.jl both use t=0 as the start time.
DEPARTURE_TIME = 0.0

# Omega (angular frequency) for Fourier series
#
# CRITICAL: This reproduces the Julia reference logic.
# The PDF [cite: 51] specifies T = 86400 seconds.
# The Julia eval.jl passes time 't' in seconds to a function using
# an omega 'ω' based on T=1440.
OMEGA = 2 * math.pi / 1440.0
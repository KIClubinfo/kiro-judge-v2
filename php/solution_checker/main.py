import random

if __name__ == "__main__":
    good_solution = bool(random.getrandbits(1))
    if good_solution:
        score = random.randint(1, 1000000)
        print(score)
    else:
        print(-1)
        nb_error = random.randint(1, 10)
        for _ in range(nb_error):
            print("This is an error.")
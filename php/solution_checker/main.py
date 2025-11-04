import argparse
from loader import load_csv_instance, load_csv_solution
from constraints import check_constraints
from cost import cost
from errors import InstanceError

# Unused imports removed:
# - from json import decoder
# - from converter import convert_instance, convert_solution
# - from solution_format_checker import solution_checker
# - from loader import * (replaced with specific imports)


def parser(instance_path, solution_path):
    """
    Loads, validates, and scores a solution for a given instance.
    
    :param instance_path: file path to the instance directory (e.g., "instances/tiny")
    :param solution_path: file path to the candidate's solution CSV (e.g., "solution.csv")
    :return: The calculated cost (float or int) if valid, or an error message (str) if invalid.
    """

    try:
        # Load the instance data (vehicles, customers, network)
        converted_instance = load_csv_instance(instance_path)
        
        # Load the participant's solution (routes)
        converted_solution = load_csv_solution(solution_path)

        # We check whether the solution verifies all constraints
        # Note: Fixed swapped arguments. It's (instance, solution).
        check_constraints(converted_instance, converted_solution)

        # We calculate its cost
        return cost(converted_instance, converted_solution)

    except FileNotFoundError:
        return "Error: File not found, parsing aborted"

    except InstanceError as errors:
        # Format the validation errors into a readable string
        message = "Error: instance is not valid, traceback list for information:\n"
        for i in errors.list:
            message += i
            message += "\n"
        return message


if __name__ == "__main__":
    # --- 1. Setup Command-Line Argument Parsing ---
    
    # The ArgumentParser method allows to create an object that will parse 
    # what is typed in the command line
    python_parser = argparse.ArgumentParser(
        description="Check if a solution is a valid solution of the Rte "
        "KIRO Problem and give it a score."
    )
    python_parser.add_argument(
        "-i", "--instance", 
        dest="instance_path", 
        default=None,
        help="Path to the instance directory (e.g., './instances/tiny')"
    )
    python_parser.add_argument(
        "-s", "--solution", 
        dest="solution_path", 
        default=None,
        help="Path to the solution file (e.g., './routes.csv')"
    )

    args = python_parser.parse_args()

    # --- 2. Validate Arguments ---
    
    # Exit if either argument is missing
    if not args.instance_path:
        print("Error: Missing instance path. Use -i or --instance.")
        exit(1)

    if not args.solution_path:
        print("Error: Missing solution path. Use -s or --solution.")
        exit(1)

    # --- 3. Run the Parser ---
    
    # Pass the file paths to the main parser function
    result = parser(args.instance_path, args.solution_path)

    # --- 4. Print the Output ---
    
    # The 'result' is either a number (the score) or a string (an error)
    if isinstance(result, (int, float)):
        # Solution was valid, print the score
        print(result)
    else:
        # Solution was invalid. Print -1 (as a score) and then the error message.
        print(-1)
        print(result)
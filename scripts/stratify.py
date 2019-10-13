import os
import sys
import re
import json

# replaces '' with 0, removes \\ to end of string, changes $value to int
def sanitize(field):
    if not field:
        return 0
    if (field[0] == '$'):
        return int(field[1:])
    return re.sub(r'\\.*', '', field)

# fname = csv file name
def csv_to_hierarchical_json(fname):
    total = {"name": "Total", "children": []}
    teams = {}
    with open(fname, "r", encoding='utf-8') as f:
        f.readline()
        colnames = [col.replace(" ", "_").replace("-", "_").lower() for col in f.readline().rstrip().split(",")]
        for row in f:
            player = {}
            for field, colname in zip(row.rstrip().split(","), colnames):
                player[colname] = sanitize(field)
            if player["tm"] not in teams:
                teams[player["tm"]] = {"name": player["tm"], "children": [player]}
            else:
                teams[player["tm"]]["children"].append(player)
    for team in teams.values():
        total["children"].append(team)
    return json.dumps(total)

if __name__ == "__main__":
    if (len(sys.argv) != 2):
        print("USAGE: python stratify.py [year_year_contracts.csv]")
        quit()
    print(csv_to_hierarchical_json(sys.argv[1]))

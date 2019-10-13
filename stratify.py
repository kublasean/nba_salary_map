import os
import sys
import re
import json

def sanitize(field):
    if not field:
        return 0
    if (field[0] == '$'):
        return int(field[1:])
    return re.sub(r'\\.*', '', field)

def main(args):
    if (len(args) != 2):
        print("USAGE: python stratify.py [year_year_contracts.csv]")
        return 1

    total = {"name": "Total", "children": []}
    teams = {}
    with open(args[1], "r", encoding='utf-8') as f:
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

    print(json.dumps(total))
    return 0

if __name__ == "__main__":
    main(sys.argv)
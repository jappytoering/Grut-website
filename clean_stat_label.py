import re

with open('style.css', 'r') as f:
    css = f.read()

# Strip out local overrides for .stat-label
css = re.sub(r'\.card--service \.stat-label\s*\{[^}]*\}', '', css)

with open('style.css', 'w') as f:
    f.write(css)

print("Cleaned .stat-label overrides!")

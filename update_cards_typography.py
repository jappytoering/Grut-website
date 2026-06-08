import re

with open('index.html', 'r') as f:
    html = f.read()

# 1. Change <h4 class="stat-value"> to <h5 class="stat-value">
html = html.replace('<h4 class="stat-value">', '<h5 class="stat-value">')
html = html.replace('</h4></span>', '</h5>') # Fix the broken closing tags while we're at it
html = html.replace('</h4>', '</h5>') # Wait, this might replace card titles too! Let's be careful.

# Let's revert and do regex for exact matching

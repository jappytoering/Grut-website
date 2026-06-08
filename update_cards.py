import re

with open('index.html', 'r') as f:
    html = f.read()

# Fix stats to h5
html = re.sub(r'<h4 class="stat-value">(.*?)</span>', r'<h5 class="stat-value">\1</h5>', html)
html = re.sub(r'<h4 class="stat-value">(.*?)</h4>', r'<h5 class="stat-value">\1</h5>', html)

# Change all <h3> to <h4> inside cards
html = re.sub(r'<h3>(.*?)</h3>', r'<h4>\1</h4>', html)
html = re.sub(r'<h3 class="card__title"(.*?)>(.*?)</h3>', r'<h4 class="card__title"\1>\2</h4>', html)

with open('index.html', 'w') as f:
    f.write(html)

with open('style.css', 'r') as f:
    css = f.read()

# Change .card h3 to .card h4
css = css.replace('.card h3 {', '.card h4 {')

# Add the margin-bottom to stats to match line-height of 1.5
# 1.5 line height means 0.5em is the gap between lines of text
# We'll just add the rule directly at the end or replace it.
if '.stat-value {' not in css:
    css += "\n.stat-value { margin-bottom: 0.5em !important; }\n"
else:
    # Just append it to be sure
    css += "\n.stat-value { margin-bottom: 0.5em !important; }\n"
    
with open('style.css', 'w') as f:
    f.write(css)

print("Done updating typography in cards.")

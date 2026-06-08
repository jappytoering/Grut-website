import re

# 1. Update HTML
with open('index.html', 'r') as f:
    html = f.read()

# Replace <span class="stat-value"> with <h4 class="stat-value">
html = html.replace('<span class="stat-value">', '<h4 class="stat-value">')
# Wait, replacing </span> for stats requires regex since we only want to replace the closing tag of stat-value.
# Instead of regex, let's do a precise regex substitute.
html = re.sub(r'<span class="stat-value">(.*?)</span>', r'<h4 class="stat-value">\1</h4>', html)

with open('index.html', 'w') as f:
    f.write(html)


# 2. Update CSS
with open('style.css', 'r') as f:
    css = f.read()

# Replace base stat-value font-size with h4-size
css = css.replace('font-size: clamp(22px, 3vh, 34px);', 'font-size: var(--h4-size);')

# Remove the media queries overrides for 1600px for card__title and stat-value
# Lines 4265 to 4274
# Let's use regex to strip out those specific blocks.

css = re.sub(r'/\* Title \*/\s*\.card--service \.card__title \{\s*font-size: 45px; /\* Even groot getrokken als de stat-waarde \*/\s*\}', '', css)

css = re.sub(r'/\* Stats \(alleen het getal 40% groter\) \*/\s*\.card--service \.stat-value \{\s*font-size: 45px; /\* Was 32px \(32 \* 1\.4 = 44\.8\) \*/\s*\}', '', css)

with open('style.css', 'w') as f:
    f.write(css)

print("HTML and CSS updated.")

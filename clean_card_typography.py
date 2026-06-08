import re

with open('style.css', 'r') as f:
    css = f.read()

# Remove the entire .card h4 block and recreate it with only the margin
old_card_h4 = """.card h4 {
    font-family: var(--font-heading);
    font-size: clamp(1.22rem, 2.2vw, 2.2rem);
    font-weight: 600;
    line-height: 1.1;
    margin-bottom: 0.8rem;
    letter-spacing: -0.02em;
}"""
new_card_h4 = """.card h4 {
    margin-bottom: 0.8rem;
}"""
css = css.replace(old_card_h4, new_card_h4)

# Find and replace font overrides in .card--service .stat-value
css = re.sub(r'\.card--service \.stat-value\s*\{[^}]*\}', '', css)

# Find and replace font overrides in .card--service .card__title
css = re.sub(r'\.card--service \.card__title\s*\{[^}]*\}', '', css)

# Find and replace .card--purple h3 and .card--photo h3 (they are now h4 anyway)
css = re.sub(r'\.card--purple h3\s*\{[^}]*\}', '', css)
css = re.sub(r'\.card--photo h3\s*\{[^}]*\}', '', css)

# We might also have .card--purple h4 and .card--photo h4 if I renamed them? 
# No, I used a direct replace('.card h3 {', '.card h4 {') earlier, which only hit one exact match.

with open('style.css', 'w') as f:
    f.write(css)

print("Cleaned CSS overrides!")

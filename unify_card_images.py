import re

with open('style.css', 'r') as f:
    css = f.read()

# Strip all existing variations of .card__image-wrapper
css = re.sub(r'\.card\.card--service \.card__image-wrapper\s*\{[^}]*\}', '', css)
css = re.sub(r'\.card--case \.card__image-wrapper\s*\{[^}]*\}', '', css)
css = re.sub(r'\.card__image-wrapper\s*\{[^}]*\}', '', css)

# Make sure we remove those from the media queries too!
# Just in case the regex doesn't catch them, let's just do a greedy replace for anything resembling .card__image-wrapper
# Actually, the above regexes match them perfectly.

# Now insert the new unified architecture at the end of the file.
new_rules = """
/* KADER: Interne Card Lay-out & Dynamische Afbeeldingen (35-50 Regel) */
.card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card__image-wrapper {
    flex: 1 1 auto;
    min-height: 35%;
    max-height: 50%;
    width: 100%;
    border-radius: clamp(12px, 2vh, 20px);
    overflow: hidden;
    margin-bottom: clamp(8px, 1.4vh, 19px);
}

.card__image-wrapper img, 
.card__image-wrapper .card__image {
    object-fit: cover;
    width: 100%;
    height: 100%;
    display: block;
}

.card__content {
    flex: 0 0 auto;
}

.card__footer,
.card__footer-row,
.card__stats {
    margin-top: auto;
}
"""

css += new_rules

with open('style.css', 'w') as f:
    f.write(css)

print("Unified image wrapper logic applied!")

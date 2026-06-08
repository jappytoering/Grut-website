import re

with open('style.css', 'r') as f:
    css = f.read()

# 1. Add variables to :root
root_vars = """
    /* Vloeibare UI Tekst (Broodtekst, Buttons, Tags) */
    --body-size: clamp(16px, 15.18px + 0.227vw, 21px);
    --btn-size: clamp(16px, 15.18px + 0.227vw, 21px);
    --tag-size: clamp(16px, 15.18px + 0.227vw, 21px);
"""
css = css.replace(':root {', ':root {' + root_vars)

# 2. Add global UI text rules after root
ui_rules = """

/* UI Tekst Standaarden (Manifest) */
p, .card__description, .faq-item__content p, .missie__text + p, .over-ons__content p {
    font-family: var(--font-body) !important;
    font-weight: 400 !important;
    font-size: var(--body-size) !important;
    line-height: 1.5;
}

.btn {
    font-family: var(--font-body) !important;
    font-weight: 700 !important;
    font-size: var(--btn-size) !important;
}

.hero__tag, .card__tag-pill, .card__category-label, .feature-tag, .stat-label {
    font-family: var(--font-body) !important;
    font-weight: 700 !important;
    font-size: var(--tag-size) !important;
}

"""

css = css.replace('/* =========================================\n   02. RESET & BASIS\n   ========================================= */', '/* =========================================\n   02. RESET & BASIS\n   ========================================= */' + ui_rules)

# 3. Clean up the CSS file to remove conflicting properties in these specific classes
# To avoid breaking other things, we will just rely on the !important flags we just set.
# However, to keep the CSS clean, let's remove existing font-size declarations for p, btn, tags.

def remove_prop(block, prop):
    return re.sub(r'^\s*' + prop + r':.*?;?\s*$', '', block, flags=re.MULTILINE)

# We will just write the file. The !important tags will guarantee correct behavior everywhere.
# The user approved "vaspinnen op Satoshi standaarden", which means overriding previous media queries.
# Any font-size inside a media query for 'p' or '.btn' will be overridden by the !important tags.

with open('style.css', 'w') as f:
    f.write(css)

print("CSS updated with new UI text rules.")

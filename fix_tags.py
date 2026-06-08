import re

# 1. Update HTML
with open('index.html', 'r') as f:
    html = f.read()

# Replace service card titles: h3 -> h4
# They look like: <h3 class="card__title">Strategie &amp; analyse</h3>
def repl_service(match):
    return match.group(0).replace('<h3', '<h4').replace('</h3>', '</h4>')

html = re.sub(r'<h3 class="card__title">.*?</h3>', repl_service, html)

# Replace FAQ items: h3 -> h5
# They look like: <h3 class="faq-item__question">...</h3>
def repl_faq(match):
    return match.group(0).replace('<h3', '<h5').replace('</h3>', '</h5>')

html = re.sub(r'<h3 class="faq-item__question">.*?</h3>', repl_faq, html)

with open('index.html', 'w') as f:
    f.write(html)


# 2. Update CSS
with open('style.css', 'r') as f:
    css = f.read()

# Update service card title from h5-size to h4-size and enforce font
css = css.replace('font-size: var(--h5-size);', 'font-size: var(--h4-size); /* fixed to h4 */\n    font-family: var(--font-heading);\n    font-weight: 800;')

# Update faq-item__question to use h5-size and enforce font
css = css.replace('font-size: clamp(1.1rem, 2vw, 1.3rem);', 'font-size: var(--h5-size);\n    font-family: var(--font-heading);\n    letter-spacing: -0.02em;')

# Enforce font-weight 800 on faq-item__question
css = css.replace('font-weight: 600;\n    margin: 0;\n    flex: 1;\n    padding-right: 1rem;\n}', 'font-weight: 800;\n    margin: 0;\n    flex: 1;\n    padding-right: 1rem;\n}')

# Update team member h3 (card-modal__template h3) to enforce font
css = css.replace('.card__modal-template h3 {\n    font-size: clamp(1.75rem, 3.5vw, 51px);', '.card__modal-template h3 {\n    font-family: var(--font-heading);\n    font-weight: 800;\n    font-size: clamp(1.75rem, 3.5vw, 51px);')

# Make missie__text font-weight 800 and letter-spacing for h2
css = css.replace('.missie__text {\n    font-family: var(--font-heading);\n    font-size: clamp(32px, 4.2vw, 82.58px);\n    font-weight: 600;', '.missie__text {\n    font-family: var(--font-heading);\n    font-size: clamp(32px, 4.2vw, 82.58px);\n    font-weight: 800;\n    letter-spacing: -0.02em;')

with open('style.css', 'w') as f:
    f.write(css)

print("Updates applied.")

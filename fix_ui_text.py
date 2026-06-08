import re

with open('style.css', 'r') as f:
    css = f.read()

# 1. Add calc() to the clamp functions to guarantee browser evaluation
css = css.replace(
    '--body-size: clamp(16px, 15.18px + 0.227vw, 21px);',
    '--body-size: clamp(16px, calc(15.18px + 0.227vw), 21px);'
)
css = css.replace(
    '--btn-size: clamp(16px, 15.18px + 0.227vw, 21px);',
    '--btn-size: clamp(16px, calc(15.18px + 0.227vw), 21px);'
)
css = css.replace(
    '--tag-size: clamp(16px, 15.18px + 0.227vw, 21px);',
    '--tag-size: clamp(16px, calc(15.18px + 0.227vw), 21px);'
)

# 2. Fix the consistency of stat-label and explicitly add card text
# Replace the old UI Tekst block with a new one
old_block = """/* UI Tekst Standaarden (Manifest) */
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
}"""

new_block = """/* UI Tekst Standaarden (Manifest) */
p, .card__description, .card p, .card__content p, .faq-item__content p, .missie__text + p, .over-ons__content p, .stat-label {
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

.hero__tag, .card__tag-pill, .card__category-label, .feature-tag {
    font-family: var(--font-body) !important;
    font-weight: 700 !important;
    font-size: var(--tag-size) !important;
}"""

css = css.replace(old_block, new_block)

with open('style.css', 'w') as f:
    f.write(css)

print("UI text fixed: calc() added to clamps, and stat-label moved to body text group.")

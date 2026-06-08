import re

with open('style.css', 'r') as f:
    css = f.read()

# Add a media query for the 5dvh logic and p margin reset
media_query = """
/* ----- Verticale Marges 13-inch+ (Landscape) ----- */
@media (min-width: 1024px) and (orientation: landscape) {
    :root {
        --spacing-y-primary: 5dvh;
        --slide-top: var(--spacing-y-primary);
        --slide-bottom: var(--spacing-y-primary);
        --gap-nav-h2: var(--spacing-y-primary);
        --gap-h2-cards: var(--spacing-y-primary);
    }
}

/* Reset P top margin globally so it doesn't break header/paragraph gaps */
p {
    margin-top: 0;
}
"""

css += media_query

with open('style.css', 'w') as f:
    f.write(css)

print("Landscape vertical rhythm logic and P margin reset applied.")

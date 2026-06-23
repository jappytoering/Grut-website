import bs4
import re

def unify_overlays():
    with open('index.html', 'r', encoding='utf-8') as f:
        html = f.read()

    soup = bs4.BeautifulSoup(html, 'html.parser')

    # 1. Remove testOverlay HTML block
    test_overlay = soup.find(id="testOverlay")
    if test_overlay:
        test_overlay.decompose()

    # 2. Replace all remaining .card__modal-template with .card-overlay-content
    new_content = """<template class="card-overlay-content">
                                    <p class="overlay-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    
                                    <img class="overlay-image" src="assets/content-afbeelding.jpg" alt="Foto 1" loading="lazy">
                                    
                                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                    
                                    <img class="overlay-image" src="assets/content-afbeelding-2.jpg" alt="Foto 2" loading="lazy">
                                    
                                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.</p>
                                    
                                    <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.</p>
                                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.</p>
                                </template>"""
    for old_template in soup.find_all(class_='card__modal-template'):
        new_soup = bs4.BeautifulSoup(new_content, 'html.parser')
        old_template.replace_with(new_soup)

    # Convert back to string
    html = str(soup)

    # 3. Clean up the inline script using regex
    # We need to replace `openOverlay(index)` with `window.openMainOverlay(index)`
    html = html.replace('openOverlay(', 'window.openMainOverlay(')
    
    # 4. We should completely remove `function buildOverlayCarousel()`, `function openOverlay(index = 0)`, `function closeOverlay()`, and their event listeners.
    # Actually, if they are just dead code, it's fine, but let's remove them to avoid bloat.
    # Instead of complex regex, let's just let them be dead code or we can remove the block.
    # Let's remove lines from `// Overlay Logica` up to `// Tab switching logic`
    html = re.sub(r'// Overlay Logica.*?// Tab switching logic', '// Tab switching logic', html, flags=re.DOTALL)

    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(html)
        
    print("Successfully unified overlays.")

if __name__ == '__main__':
    unify_overlays()

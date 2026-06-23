import sys
import os

# We will use simple string replacement but targeting exactly the sections we know
# Wait, actually since I want it to be 100% safe, I will use Python's built-in html.parser or just write a small parser.
# Actually, the user has 10 cards. I can just search for `<div class="card-modal__content-slot">` and the exact next `</div>` 
# if there are no nested divs. Wait, there are nested divs (like `.overlay-main-image-wrapper`).
# Let's use standard string manipulation to find the balancing `</div>`.

def replace_content_slots():
    with open('index.html', 'r', encoding='utf-8') as f:
        html = f.read()

    new_content = """<template class="card-overlay-content">
                                    <p class="overlay-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    
                                    <img class="overlay-image" src="assets/content-afbeelding.jpg" alt="Foto 1" loading="lazy">
                                    
                                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                    
                                    <img class="overlay-image" src="assets/content-afbeelding-2.jpg" alt="Foto 2" loading="lazy">
                                    
                                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.</p>
                                </template>"""

    # We will find each <div class="card-modal__content-slot"> or <div class="card__modal-template" hidden><div class="card-modal__content-slot">
    # Wait, earlier they used <div class="card__modal-template" hidden><div class="card-modal__content-slot">
    # Let's just find the exact text in `b5b5a68`.
    
    import bs4
    soup = bs4.BeautifulSoup(html, 'html.parser')
    
    # Remove all existing card-modal__content-slot
    # BUT wait! Some slots are wrapped in <div class="card__modal-template" hidden>
    # We will replace the whole wrapper or just the slot.
    for slot in soup.find_all(class_='card-modal__content-slot'):
        parent = slot.parent
        # Create new template tag
        template = soup.new_tag("template", attrs={"class": "card-overlay-content"})
        # Parse the new content and append
        new_soup = bs4.BeautifulSoup(new_content, 'html.parser')
        # Replace the parent if it is card__modal-template, otherwise replace the slot itself
        if parent and 'card__modal-template' in parent.get('class', []):
            parent.replace_with(new_soup)
        else:
            slot.replace_with(new_soup)

    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(str(soup))
        
    print("Successfully replaced all content slots using BeautifulSoup.")

if __name__ == '__main__':
    try:
        import bs4
        replace_content_slots()
    except ImportError:
        import subprocess
        subprocess.check_call([sys.executable, "-m", "pip", "install", "beautifulsoup4"])
        import bs4
        replace_content_slots()

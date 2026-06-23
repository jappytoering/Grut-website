import re

def update_html():
    with open('index.html', 'r', encoding='utf-8') as f:
        html = f.read()

    # We want to replace the content of EVERY <div class="card-modal__content-slot">
    # with 4 paragraphs of lorem ipsum.
    
    lorem = """
                                    <p class="overlay-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.</p>
    """
    
    # Regex to find <div class="card-modal__content-slot"> ... </div>
    # Note: we need to handle nested divs carefully. 
    # The templates in index.html usually just contain h4 and p tags, no nested divs.
    
    pattern = re.compile(r'(<div class="card-modal__content-slot">).*?(</div>)', re.DOTALL)
    
    new_html = pattern.sub(r'\1' + lorem + r'\2', html)
    
    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(new_html)
        
    print("Updated index.html with Lorem Ipsum.")

if __name__ == '__main__':
    update_html()

import bs4

def double_intro_text():
    with open('index.html', 'r', encoding='utf-8') as f:
        html = f.read()

    soup = bs4.BeautifulSoup(html, 'html.parser')

    intro_text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."

    for template in soup.find_all('template', class_='card-overlay-content'):
        content_soup = bs4.BeautifulSoup(template.string or template.decode_contents(), 'html.parser')
        intro_p = content_soup.find('p', class_='overlay-intro')
        if intro_p:
            intro_p.string = intro_text
            template.clear()
            template.append(bs4.BeautifulSoup(str(content_soup), 'html.parser'))

    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(str(soup))
        
    print("Successfully doubled intro text.")

if __name__ == '__main__':
    double_intro_text()

import bs4

def assign_themes():
    with open('index.html', 'r', encoding='utf-8') as f:
        html = f.read()

    soup = bs4.BeautifulSoup(html, 'html.parser')

    cards = [c for c in soup.find_all(class_='card') if c.find(class_='card-overlay-content') or c.find(class_='card__modal-template')]
    
    for card in cards:
        # Determine theme based on title or other markers
        title_attr = card.get('data-modal-title', '').lower()
        title_elem = card.find('h4')
        if title_elem and not title_attr:
            title_attr = title_elem.get_text(strip=True).lower()
        
        theme = "theme-blue" # Default
        
        # 1. Services
        if "strategie" in title_attr:
            theme = "theme-yellow"
        elif "design" in title_attr or "ux/ui" in title_attr:
            theme = "theme-green"
        elif "groei" in title_attr:
            theme = "theme-pink"
            
        # 2. Portfolio (recent werk)
        elif "daklab" in title_attr:
            theme = "theme-darkblue"
        elif "magister" in title_attr:
            theme = "theme-purple"
        elif "ai experim" in title_attr:
            theme = "theme-darkblue"
        elif "conversie" in title_attr:
            theme = "theme-purple"
        elif "usability" in title_attr:
            theme = "theme-darkblue"
        elif "sanoma" in title_attr:
            theme = "theme-pink"
        elif "werkwijze" in title_attr:
            theme = "theme-darkblue"
            
        # 3. Team
        elif "jappy" in title_attr:
            theme = "theme-pink"
        elif "jurrit" in title_attr:
            theme = "theme-yellow"
        elif "vrienden" in title_attr or "freelance" in title_attr and "ux" not in title_attr and "design" not in title_attr:
            # Let's check text for "vrienden"
            pass # We'll do a fallback for team cards
            
        card['data-overlay-theme'] = theme

    # Try to find "vrienden" specifically
    for card in cards:
        text = card.get_text(strip=True).lower()
        if "vrienden" in text and "team" in text:
            card['data-overlay-theme'] = "theme-purple"
            
        if "grut & friends" in text:
            card['data-overlay-theme'] = "theme-purple"

    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(str(soup))
        
    print("Successfully assigned themes.")

if __name__ == '__main__':
    assign_themes()

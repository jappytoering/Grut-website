import re

def main():
    css_file = 'style.css'
    
    with open(css_file, 'r', encoding='utf-8') as f:
        css = f.read()

    # 1. Update .card-modal__slide blur and background
    old_slide = """    /* Glassmorphism base */
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-left: 1px solid rgba(255,255,255,0.05);
    overflow-y: auto;"""
    
    new_slide = """    /* Glassmorphism base */
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(45, 39, 90, 0.7); /* Purple with 70% opacity */
    border-left: 1px solid rgba(255,255,255,0.05);
    overflow-y: auto;"""
    
    css = css.replace(old_slide, new_slide)
    
    # 2. Remove Theme Colors block
    # We will use regex to remove it
    css = re.sub(r'/\* Theme Colors \*/.*?/\* Header \*/', '/* Header */', css, flags=re.DOTALL)
    
    # 3. Update overlay-header
    old_header = """.overlay-header {
    position: sticky;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px var(--grid-margin);
    z-index: 10;
    pointer-events: none; /* Let clicks pass through header area */
}"""
    
    new_header = """.overlay-header {
    position: sticky;
    top: 0;
    left: 0;
    width: 100%;
    max-width: 680px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px var(--grid-margin);
    z-index: 10;
    pointer-events: none; /* Let clicks pass through header area */
}"""
    css = css.replace(old_header, new_header)
    
    # 4. Remove theme-yellow / theme-green overrides
    css = re.sub(r'\.theme-yellow \.overlay-header__tag, \.theme-green \.overlay-header__tag \{.*?\}', '', css, flags=re.DOTALL)
    css = re.sub(r'\.theme-yellow \.overlay-footer, \.theme-green \.overlay-footer \{.*?\}', '', css, flags=re.DOTALL)
    css = re.sub(r'\.theme-yellow \.overlay-footer-btn--primary, \.theme-green \.overlay-footer-btn--primary \{.*?\}', '', css, flags=re.DOTALL)
    css = re.sub(r'\.theme-yellow \.overlay-footer-btn--secondary, \.theme-green \.overlay-footer-btn--secondary \{.*?\}', '', css, flags=re.DOTALL)
    
    # 5. Add .overlay-intro
    intro_css = """
.overlay-flexible-content .overlay-intro {
    font-size: 24px;
    font-weight: 500;
    line-height: 1.4;
    margin-bottom: 32px;
}
"""
    # Insert it before /* Footer Actions */
    css = css.replace("/* Footer Actions */", intro_css + "\n/* Footer Actions */")

    with open(css_file, 'w', encoding='utf-8') as f:
        f.write(css)
        
    print("Updated style.css")

if __name__ == '__main__':
    main()

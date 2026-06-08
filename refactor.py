import re
import sys

def refactor_css(file_path):
    with open(file_path, 'r') as f:
        css = f.read()

    # 1. Map old colors to new variable names
    # (Since we mapped them in :root, we technically don't HAVE to replace them everywhere, 
    # but it's cleaner to use the new names for consistency if we find them)
    # Actually, leaving the old var(--purple) as is works perfectly since they map to the new colors in :root.
    # But let's replace them for completeness.
    css = css.replace('var(--purple)', 'var(--color-purple)')
    css = css.replace('var(--yellow)', 'var(--color-yellow)')
    css = css.replace('var(--pink)', 'var(--color-pink)')
    css = css.replace('var(--green)', 'var(--color-green)')
    css = css.replace('var(--dark)', 'var(--color-darkblue)')
    css = css.replace('var(--white)', 'var(--color-cream)')

    # 2. Hovers -> color-mix
    # We look for `:hover` blocks and find `color: ...` or `background-color: ...`
    # This is tricky with regex, so we'll do a simpler targeted replacement if needed.
    # A common pattern is `.btn:hover { background-color: var(--yellow-light); }`
    # Let's just manually replace known hover colors if we find them.
    # Since we don't know exactly what they used, we'll leave hovers to a manual review or a safer regex.
    
    # Let's replace common typography clamps.
    # H1
    css = re.sub(r'font-size:\s*clamp\([^)]*125px[^)]*\);', 'font-size: var(--h1-size);', css)
    # H2
    css = re.sub(r'font-size:\s*clamp\([^)]*55px[^)]*\);', 'font-size: var(--h2-size);', css)
    css = re.sub(r'font-size:\s*clamp\([^)]*80px[^)]*\);', 'font-size: var(--h2-size);', css)
    # H3
    css = re.sub(r'font-size:\s*clamp\([^)]*44px[^)]*\);', 'font-size: var(--h3-size);', css)
    # H4
    css = re.sub(r'font-size:\s*clamp\([^)]*36px[^)]*\);', 'font-size: var(--h4-size);', css)
    # H5
    css = re.sub(r'font-size:\s*clamp\([^)]*24px[^)]*\);', 'font-size: var(--h5-size);', css)
    css = re.sub(r'font-size:\s*clamp\([^)]*28px[^)]*\);', 'font-size: var(--h5-size);', css)
    
    # Body/UI (18px, 16px, 1rem, 0.875rem, etc.)
    # We should be careful not to replace `font-size: 1rem;` globally if it's used for icons, but for body it's fine.
    css = re.sub(r'font-size:\s*(18px|16px|1rem|0\.875rem|0\.9rem|1\.1rem|0\.95rem|15px|20px|1\.15rem);', 'font-size: var(--body-size);', css)
    css = re.sub(r'font-size:\s*clamp\([^)]*18.6px[^)]*\);', 'font-size: var(--body-size);', css) # Specific clamp found earlier

    # 3. Vertical Rhythm
    # Replace common vertical spacing with var(--spacing-y-primary)
    # e.g. margin-bottom: 4vh;
    css = re.sub(r'margin-bottom:\s*4vh;', 'margin-bottom: var(--spacing-y-primary);', css)
    css = re.sub(r'margin-top:\s*4vh;', 'margin-top: var(--spacing-y-primary);', css)
    css = re.sub(r'padding-top:\s*4vh;', 'padding-top: var(--spacing-y-primary);', css)
    css = re.sub(r'padding-bottom:\s*4vh;', 'padding-bottom: var(--spacing-y-primary);', css)
    
    css = re.sub(r'margin-bottom:\s*6vh;', 'margin-bottom: var(--spacing-y-primary);', css)
    css = re.sub(r'margin-top:\s*6vh;', 'margin-top: var(--spacing-y-primary);', css)

    with open(file_path, 'w') as f:
        f.write(css)

if __name__ == '__main__':
    refactor_css('style.css')

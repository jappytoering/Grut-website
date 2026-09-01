import os

source_file = 'components/prototype_sprint.php'
dest_dir = 'components/'

with open(source_file, 'r', encoding='utf-8') as f:
    content = f.read()

hero_start = '<div class="overlay-header__tags"'
slider_start = '<!-- Hero Auto Slider (replacing static image) -->'
how_it_works_start = '<h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t(\'prototype.how_it_works.title\', \'Hoe het werkt?\'); ?></h3>'
meta_list_start = '<h3 style="margin-top: 3rem; margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t(\'prototype.short_route.title\', \'Ons traject in het kort\'); ?></h3>'
highlight_block_start = '<!-- Uitgelicht blok -->'
columns_block_start = '<!-- Opsomming blok -->'
fit_start = '<h4 style="margin-bottom: 0.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"'
days_list_start = '<div class="prototype-days-list">'
faq_start = '<div class="prototype-faq">'
trusted_logos_start = '<div class="prototype-trusted-block">'
cta_block_start = '<!-- FORMULIER SECTIE START -->'

sections = [
    ('sprint_hero', hero_start, slider_start),
    ('sprint_slider', slider_start, how_it_works_start),
    ('sprint_text_checklist', how_it_works_start, meta_list_start),
    ('sprint_meta_list', meta_list_start, fit_start),
    ('sprint_fit', fit_start, highlight_block_start),
    ('sprint_highlight_block', highlight_block_start, columns_block_start),
    ('sprint_columns_block', columns_block_start, days_list_start),
    ('sprint_days_list', days_list_start, faq_start),
    ('sprint_faq', faq_start, trusted_logos_start),
    ('sprint_trusted_logos', trusted_logos_start, cta_block_start),
    ('sprint_cta_block', cta_block_start, None)
]

header_html = content[:content.find(hero_start)]
# Find the end of cta block: it's followed by some closing divs and script tag
cta_start_idx = content.find(cta_block_start)
script_idx = content.find('<script>', cta_start_idx)

# cta block ends just before script tag (or just before the closing divs)
cta_block_end = content[cta_start_idx:script_idx].rfind('</div>\n    </div>\n</div>')
if cta_block_end != -1:
    cta_block_end = cta_start_idx + cta_block_end
else:
    cta_block_end = script_idx

footer_html = content[cta_block_end:]
sections[-1] = ('sprint_cta_block', cta_block_start, cta_block_end)

with open(os.path.join(dest_dir, 'sprint_header.php'), 'w', encoding='utf-8') as f:
    f.write(header_html)

for comp_name, start_str, end_val in sections:
    start_idx = content.find(start_str)
    if isinstance(end_val, str):
        end_idx = content.find(end_val, start_idx)
    else:
        end_idx = end_val
        
    if start_idx == -1 or end_idx == -1:
        print(f"Error finding bounds for {comp_name}")
        continue
        
    comp_content = content[start_idx:end_idx]
    with open(os.path.join(dest_dir, f'{comp_name}.php'), 'w', encoding='utf-8') as f:
        f.write(comp_content)

with open(os.path.join(dest_dir, 'sprint_footer.php'), 'w', encoding='utf-8') as f:
    f.write(footer_html)

print("Split completed successfully!")

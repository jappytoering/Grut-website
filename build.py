import rcssmin
import rjsmin

def minify_css():
    with open('style.css', 'r', encoding='utf-8') as f:
        css = f.read()
    minified = rcssmin.cssmin(css)
    with open('style.min.css', 'w', encoding='utf-8') as f:
        f.write(minified)
    print(f"Minified style.css: {len(css)} bytes -> {len(minified)} bytes")

def minify_js():
    with open('script.js', 'r', encoding='utf-8') as f:
        js = f.read()
    minified = rjsmin.jsmin(js)
    with open('script.min.js', 'w', encoding='utf-8') as f:
        f.write(minified)
    print(f"Minified script.js: {len(js)} bytes -> {len(minified)} bytes")

if __name__ == '__main__':
    print("Starting minification...")
    minify_css()
    minify_js()
    print("Done!")

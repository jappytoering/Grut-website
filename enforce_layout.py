css_block = """

/* ============================================
   KADER 6: LAY-OUT ARCHITECTUUR & HORIZONTALE SPATIERING
   ============================================ */

/* 1. Standaard Content (Pagina's zonder cards) */
.container, .content-slide {
    max-width: 2540px !important;
    margin: 0 auto !important;
    padding-left: 8dvw !important;
    padding-right: 8dvw !important;
}

@media (min-width: 1024px) {
    .container, .content-slide {
        padding-left: min(8dvw, 203px) !important;
        padding-right: min(8dvw, 203px) !important;
    }
}

/* 2. Slider Content (Pagina's met horizontal scroll cards) */

/* Base: Portrait (Mobiel / Staand) */
.cards-scroll {
    padding-left: 8dvw !important;
    padding-right: 12dvw !important;
    gap: 4dvw !important;
    scroll-snap-type: x mandatory !important;
    display: flex !important;
    overflow-x: auto !important;
    scrollbar-width: none !important; /* Firefox */
}

.cards-scroll::-webkit-scrollbar {
    display: none !important; /* Safari/Chrome */
}

.card {
    width: 80dvw !important;
    flex: 0 0 80dvw !important;
    min-width: 0 !important; /* Forceert het verwijderen van legacy min-widths */
    scroll-snap-align: start !important;
}

/* Landscape (13" Laptop t/m 27" UHD) */
@media (min-width: 1024px) {
    .cards-scroll {
        padding-left: min(8dvw, 203px) !important;
        padding-right: min(8dvw, 203px) !important;
        gap: 2dvw !important;
        /* Default: Centreer de cards op het scherm */
        justify-content: center !important; 
    }
    
    /* Slimme uitlijning: Als de CSS detecteert dat er 4 cards (of meer) zijn, forceer links uitlijnen voor de 'peeking' overloop */
    .cards-scroll:has(.card:nth-child(4)) {
        justify-content: flex-start !important;
    }

    .card {
        /* Precies 3 cards passen (met 2 gaps van 2dvw, dus 4dvw totaal) */
        width: calc((100% - 4dvw) / 3) !important;
        flex: 0 0 calc((100% - 4dvw) / 3) !important;
    }
}

/* 3. Scroll Padding voor Sticky Menu (Veilige ankerlinks) */
html {
    scroll-padding-top: var(--nav-height, 120px) !important;
}
"""

with open('style.css', 'a') as f:
    f.write(css_block)

print("Kader 6 Layout Architecture successfully appended to style.css")

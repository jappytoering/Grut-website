/* ============================================
   GRUT — Slide Architecture Navigation
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    const slidesContainer = document.getElementById('slidesContainer');
    const nav = document.getElementById('nav');
    const navLogo = document.getElementById('navLogo');
    const navStateText = document.getElementById('navStateText');
    const navClose = document.getElementById('navClose');
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const sections = document.querySelectorAll('.slide');

    // ---- Scroll Reveal with Blur Effect ----
    const revealElements = document.querySelectorAll('.reveal');
    if (revealElements.length > 0 && slidesContainer) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            root: slidesContainer,
            rootMargin: '0px 0px -60px 0px'
        });
        revealElements.forEach(el => revealObserver.observe(el));
    }

    // ---- Hero Tags "Meer +" Toggle ----
    const heroMeer = document.getElementById('heroMeer');
    const extraTags = document.querySelectorAll('.hero__tag--extra');
    let tagsExpanded = false;

    if (heroMeer) {
        heroMeer.addEventListener('click', () => {
            tagsExpanded = !tagsExpanded;

            if (tagsExpanded) {
                heroMeer.classList.add('hero__meer--close');
                extraTags.forEach((tag, i) => {
                    tag.style.display = 'inline-flex';
                    tag.style.opacity = '0';
                    tag.style.transform = 'translateY(6px) scale(0.92)';
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            tag.style.transition = 'opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1), transform 0.28s cubic-bezier(0.16, 1, 0.3, 1)';
                            tag.style.opacity = '1';
                            tag.style.transform = 'translateY(0) scale(1)';
                        }, i * 28);
                    });
                });
            } else {
                heroMeer.classList.remove('hero__meer--close');
                extraTags.forEach((tag) => {
                    tag.style.transition = 'opacity 0.16s ease, transform 0.16s ease';
                    tag.style.opacity = '0';
                    tag.style.transform = 'translateY(3px) scale(0.95)';
                    setTimeout(() => {
                        tag.style.display = 'none';
                        tag.style.transition = '';
                        tag.style.transform = '';
                    }, 160);
                });
            }
        });
    }

    // ---- Logo Scroll Animation (wordmark → beeldmerk) ----
    if (slidesContainer && nav) {
        slidesContainer.addEventListener('scroll', () => {
            if (slidesContainer.scrollTop > 80) {
                nav.classList.add('nav--scrolled');
            } else {
                nav.classList.remove('nav--scrolled');
            }
        }, { passive: true });
    }

    // ---- Home Icon: Scroll-to-top on click ----
    if (navLogo && slidesContainer) {
        navLogo.addEventListener('click', (e) => {
            if (nav.classList.contains('nav--scrolled')) {
                e.preventDefault();
                closePanel(); // also close any open menu
                slidesContainer.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                e.preventDefault();
            }
        });
    }

    // ---- Nav Theme Color Detection ----
    // Past de kleur (donker/licht) van glas navigatie aan obv huidige slide data-nav-theme
    const themeSections = document.querySelectorAll('.slide[data-nav-theme]');
    let currentTheme = 'dark';

    const themeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Ensure the intersecting slide takes up a decent chunk of screen before switching
                const theme = entry.target.getAttribute('data-nav-theme');
                if (theme && theme !== currentTheme) {
                    currentTheme = theme;
                    nav.classList.remove('nav--theme-light', 'nav--theme-dark');
                    nav.classList.add(`nav--theme-${theme}`);
                }
            }
        });
    }, {
        root: slidesContainer,
        rootMargin: '-10% 0px -80% 0px', // Snap early when top of next slide enters
        threshold: 0
    });

    themeSections.forEach(section => themeObserver.observe(section));
    if (nav) nav.classList.add('nav--theme-dark');

    // ---- Active Slide State Tracking (Update Menu Text automatically) ----
    const slideObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                let activeLabel = '';
                let activeLinkId = '';

                // Map the slide IDs to the main menu categories
                if (id.includes('hero')) { activeLabel = 'Home'; }
                else if (id.includes('over-ons')) { activeLabel = 'Over ons'; activeLinkId = 'navOverOnsLink'; }
                else if (id.includes('diensten')) { activeLabel = 'Diensten'; activeLinkId = 'navDienstenLink'; }
                else if (id.includes('portfolio')) { activeLabel = 'Portfolio'; activeLinkId = 'navPortfolioLink'; }
                else if (id.includes('team')) { activeLabel = 'Team'; activeLinkId = 'navTeamLink'; }
                else if (id.includes('contact')) { activeLabel = 'Contact'; }

                // Update Mobile Tracker Label
                if (activeLabel && activeLabel !== 'Home') {
                    if (!nav.className.includes('-open')) {
                        nav.classList.add('nav--state-active');
                        transitionStateText(activeLabel);
                    }
                } else {
                    if (!nav.className.includes('-open')) {
                        nav.classList.remove('nav--state-active');
                    }
                }

                // Update Desktop Links highlighting (yellow color)
                document.querySelectorAll('.nav__links a').forEach(link => {
                    link.classList.remove('active');
                });
                if (activeLinkId) {
                    const link = document.getElementById(activeLinkId);
                    if (link) link.classList.add('active');
                }
            }
        });
    }, {
        root: slidesContainer,
        threshold: 0.5 // trigger when a slide is firmly in the middle of the screen
    });

    sections.forEach(slide => slideObserver.observe(slide));


    // ---- Navigation Panel System ----
    const panelTriggers = {
        'navCtaBtn': 'contact',
        'navTeamLink': 'team',
        'navOverOnsLink': 'over-ons',
        'navDienstenLink': 'diensten',
        'navPortfolioLink': 'portfolio'
    };

    function openPanel(type) {
        closePanel(); // reset first
        nav.classList.add(`nav--${type}-open`);
        
        const labels = {
            'contact': 'Contact',
            'team': 'Team',
            'over-ons': 'Over ons',
            'diensten': 'Diensten',
            'portfolio': 'Portfolio'
        };
        transitionStateText(labels[type] || '');
    }

    function closePanel() {
        nav.classList.remove('nav--contact-open', 'nav--team-open', 'nav--over-ons-open', 'nav--diensten-open', 'nav--portfolio-open', 'nav--phone-open', 'nav--mail-open');
        // Let intersection observer take back control of the label
        setTimeout(() => {
            if (!nav.className.includes('-open')) {
                // re-evaluate which slide is active by triggering a fake scroll event 
                // (or just letting the observer re-run naturally)
                navStateText.textContent = '';
                nav.classList.remove('nav--state-active');
            }
        }, 100);
    }

    // For elements in panelTriggers that are NOT anchors (like Contact button)
    Object.entries(panelTriggers).forEach(([id, type]) => {
        const el = document.getElementById(id);
        if (el && el.tagName !== 'A') {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                openPanel(type);
            });
        }
    });

    if (navClose) navClose.addEventListener('click', closePanel);

    function transitionStateText(newText) {
        if (!navStateText || navStateText.textContent === newText) return;
        navStateText.style.transition = 'opacity 0.2s var(--ease-out), transform 0.2s var(--ease-out)';
        navStateText.style.opacity = '0';
        navStateText.style.transform = 'translateY(-5px)';
        
        setTimeout(() => {
            navStateText.textContent = newText;
            navStateText.style.transform = 'translateY(5px)';
            requestAnimationFrame(() => {
                navStateText.style.opacity = '1';
                navStateText.style.transform = 'translateY(0)';
                setTimeout(() => {
                    navStateText.style.transition = '';
                    navStateText.style.transform = '';
                }, 200);
            });
        }, 200);
    }

    // ---- Smooth Scroll for Anchor Links (Magnetism Support) ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (href === '#') return; // Ignore empty hashes
            
            const target = document.querySelector(href);
            if (!target || !slidesContainer) return;

            // Check if this anchor is a panel trigger
            const triggerEntry = Object.entries(panelTriggers).find(([id, type]) => anchor.id === id);
            if (triggerEntry) {
                const [id, type] = triggerEntry;
                const isOpen = nav.classList.contains(`nav--${type}-open`);
                
                if (!isOpen) {
                    // First click: OPEN the panel, prevent scroll
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    openPanel(type);
                    return;
                }
                // Second click: It is already open! Close panel and let it scroll.
            }

            e.preventDefault();
            closePanel(); // Close menu if open
            
            if (hamburger) hamburger.classList.remove('active');
            if (mobileMenu) mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
            
            target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // ---- Connect Contact Sub-Panels (Telephone / Mail) ----
    const subPanelStates = {
        'navViaTelefoon': ['nav--phone-open', 'Via telefoon', false],
        'navViaMail': ['nav--mail-open', 'Via mail', false],
        'navMailBack': ['nav--mail-open', 'Contact', true],
        'navPhoneBack': ['nav--phone-open', 'Contact', true]
    };

    Object.entries(subPanelStates).forEach(([id, [cssClass, label, isRemove]]) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', () => {
                isRemove ? nav.classList.remove(cssClass) : nav.classList.add(cssClass);
                transitionStateText(label);
            });
        }
    });

    function animateCopyText(btn, newText) {
        const span = btn.querySelector('span');
        if (!span || span.textContent === newText) return;
        const origText = span.textContent;
        // Simplified anim
        span.textContent = newText;
        btn.classList.add('nav__panel-item--copied');
        setTimeout(() => {
            span.textContent = origText;
            btn.classList.remove('nav__panel-item--copied');
        }, 1600);
    }

    // ---- Connect Copy Buttons ----
    const copyTargets = [
        ['navCopyPhone', 'mobCopyPhone', '06 20869929'], 
        ['navCopyEmail', 'mobCopyEmail', 'letsgo@grutdesigners.nl']
    ];

    copyTargets.forEach(([navId, mobId, text]) => {
        [document.getElementById(navId), document.getElementById(mobId)].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    navigator.clipboard.writeText(text).then(() => animateCopyText(btn, 'Gekopieerd!'));
                });
            }
        });
    });

    // ---- Mobile Hamburger ----
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });
    }
});

/* ============================================
   GRUT — Enhanced Animations & Interactivity
   iOS Liquid Glass · Smooth Transitions
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ---- Scroll Reveal with Blur Effect ----
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -60px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));

    // ---- Navigation ----
    const nav = document.getElementById('nav');
    const navLogo = document.getElementById('navLogo');

    // ---- Logo Scroll Animation (wordmark → beeldmerk) ----
    window.addEventListener('scroll', () => {
        if (window.scrollY > 80) {
            nav.classList.add('nav--scrolled');
        } else {
            nav.classList.remove('nav--scrolled');
        }
    }, { passive: true });

    // ---- Home Icon: Scroll-to-top on click (only when scrolled) ----
    if (navLogo) {
        navLogo.addEventListener('click', (e) => {
            // Only act as home button when beeldmerk is visible (nav--scrolled)
            if (nav.classList.contains('nav--scrolled')) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Already at top — do nothing (prevent any jump)
                e.preventDefault();
            }
        });
    }

    // ---- Nav Theme Color Detection ----
    // Observe which section is behind the nav and apply matching color theme
    const themeSections = document.querySelectorAll('[data-nav-theme]');
    let currentTheme = 'dark';

    const themeObserver = new IntersectionObserver((entries) => {
        // Find the section that currently overlaps the nav area
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const theme = entry.target.getAttribute('data-nav-theme');
                if (theme !== currentTheme) {
                    currentTheme = theme;
                    nav.classList.remove('nav--theme-light', 'nav--theme-dark');
                    nav.classList.add(`nav--theme-${theme}`);
                }
            }
        });
    }, {
        // rootMargin: only observe a thin strip at the very top of the viewport
        // where the nav sits (roughly 0-60px from top)
        rootMargin: '-0px 0px -95% 0px',
        threshold: 0
    });

    themeSections.forEach(section => themeObserver.observe(section));
    
    // Set initial theme
    nav.classList.add('nav--theme-dark');

    // ---- Navigation Panel System ----
    const navCtaBtn    = document.getElementById('navCtaBtn');
    const navTeamLink  = document.getElementById('navTeamLink');
    const navOverOnsLink = document.getElementById('navOverOnsLink');
    const navDienstenLink = document.getElementById('navDienstenLink');
    const navPortfolioLink = document.getElementById('navPortfolioLink');
    const navClose     = document.getElementById('navClose');
    const navStateText = document.getElementById('navStateText');

    function openPanel(type) {
        // Close all
        nav.classList.remove('nav--contact-open', 'nav--team-open', 'nav--over-ons-open', 'nav--diensten-open', 'nav--portfolio-open', 'nav--phone-open', 'nav--mail-open');
        nav.classList.add(`nav--${type}-open`);
        
        // Update state label
        const labels = {
            'contact': 'Contact',
            'team': 'Team',
            'over-ons': 'Over ons',
            'diensten': 'Diensten',
            'portfolio': 'Portfolio'
        };
        navStateText.textContent = labels[type] || '';
    }

    function closePanel() {
        nav.classList.remove('nav--contact-open', 'nav--team-open', 'nav--over-ons-open', 'nav--diensten-open', 'nav--portfolio-open', 'nav--phone-open', 'nav--mail-open');
    }

    if (navCtaBtn) {
        navCtaBtn.addEventListener('click', () => openPanel('contact'));
    }

    if (navTeamLink) {
        navTeamLink.addEventListener('click', (e) => { e.preventDefault(); openPanel('team'); });
    }
    if (navOverOnsLink) {
        navOverOnsLink.addEventListener('click', (e) => { e.preventDefault(); openPanel('over-ons'); });
    }
    if (navDienstenLink) {
        navDienstenLink.addEventListener('click', (e) => { e.preventDefault(); openPanel('diensten'); });
    }
    if (navPortfolioLink) {
        navPortfolioLink.addEventListener('click', (e) => { e.preventDefault(); openPanel('portfolio'); });
    }

    if (navClose) {
        navClose.addEventListener('click', closePanel);
    }

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

    // "Via telefoon" → open phone sub-panel
    const navViaTelefoon = document.getElementById('navViaTelefoon');
    if (navViaTelefoon) {
        navViaTelefoon.addEventListener('click', () => {
            nav.classList.add('nav--phone-open');
            transitionStateText('Via telefoon');
        });
    }

    // "Via mail" → open mail sub-panel
    const navViaMail = document.getElementById('navViaMail');
    if (navViaMail) {
        navViaMail.addEventListener('click', () => {
            nav.classList.add('nav--mail-open');
            transitionStateText('Via mail');
        });
    }

    const navMailBack = document.getElementById('navMailBack');
    if (navMailBack) {
        navMailBack.addEventListener('click', () => {
            nav.classList.remove('nav--mail-open');
            transitionStateText('Contact');
        });
    }
    
    // Copy functionality for phone
    function animateCopyText(btn, newText) {
        const span = btn.querySelector('span');
        if (!span || span.textContent === newText) return;
        
        const origText = span.textContent;
        
        // Fade out original
        span.style.transition = 'opacity 0.15s var(--ease-out), transform 0.15s var(--ease-out)';
        span.style.opacity = '0';
        span.style.transform = 'translateY(-3px)';
        
        setTimeout(() => {
            span.textContent = newText;
            btn.classList.add('nav__panel-item--copied');
            span.style.transform = 'translateY(3px)';
            
            // Fade in copied text
            requestAnimationFrame(() => {
                span.style.opacity = '1';
                span.style.transform = 'translateY(0)';
            });
            
            // Revert after delay
            setTimeout(() => {
                span.style.opacity = '0';
                span.style.transform = 'translateY(-3px)';
                
                setTimeout(() => {
                    span.textContent = origText;
                    btn.classList.remove('nav__panel-item--copied');
                    span.style.transform = 'translateY(3px)';
                    
                    requestAnimationFrame(() => {
                        span.style.opacity = '1';
                        span.style.transform = 'translateY(0)';
                        
                        setTimeout(() => {
                            span.style.transition = '';
                        }, 150);
                    });
                }, 150);
            }, 1600);
        }, 150);
    }

    const navCopyPhone = document.getElementById('navCopyPhone');
    if (navCopyPhone) {
        navCopyPhone.addEventListener('click', () => {
            navigator.clipboard.writeText('06 20869929').then(() => {
                animateCopyText(navCopyPhone, 'Gekopieerd!');
            });
        });
    }
    
    const navCopyEmail = document.getElementById('navCopyEmail');
    if (navCopyEmail) {
        navCopyEmail.addEventListener('click', () => {
            navigator.clipboard.writeText('letsgo@grutdesigners.nl').then(() => {
                animateCopyText(navCopyEmail, 'Gekopieerd!');
            });
        });
    }

    // Back button → return to main contact level
    const navPhoneBack = document.getElementById('navPhoneBack');
    if (navPhoneBack) {
        navPhoneBack.addEventListener('click', () => {
            nav.classList.remove('nav--phone-open');
            transitionStateText('Contact');
        });
    }

    // Team member click: close panel + scroll to team section
    document.querySelectorAll('.nav__team-member').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            closePanel();
            const target = document.querySelector('#team');
            if (target) {
                const pos = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top: pos, behavior: 'smooth' });
            }
        });
    });

    // Contact panel item click: close panel after brief delay (skip button-only items)
    document.querySelectorAll('.nav__panel-item:not(.nav__panel-item--btn)').forEach(item => {
        item.addEventListener('click', () => {
            setTimeout(closePanel, 150);
        });
    });

    // ---- Smooth Scroll for other Anchor Links ----
    document.querySelectorAll('a[href^="#"]:not(.nav__team-member):not(#navTeamLink):not(#navOverOnsLink):not(#navDienstenLink):not(#navPortfolioLink)').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                const pos = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top: pos, behavior: 'smooth' });
            }
        });
    });

    // ---- Mobile Hamburger + Glass Overlay ----
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
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

    // ---- Contact Form (visual feedback) ----

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const btn = contactForm.querySelector('button[type="submit"]');
            const originalText = btn.textContent;

            btn.textContent = 'Verstuurd! ✓';
            btn.style.background = 'var(--green)';
            btn.style.borderColor = 'rgba(58, 209, 124, 0.3)';
            btn.disabled = true;

            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.disabled = false;
                contactForm.reset();
            }, 3000);
        });
    }

    // ---- Parallax on Hero Background ----
    const heroBg = document.querySelector('.hero__bg img');
    if (heroBg && window.innerWidth > 768) {
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            const hero = document.querySelector('.hero');
            if (scrollY < hero.offsetHeight) {
                heroBg.style.transform = `scale(1.08) translateY(${scrollY * 0.12}px)`;
            }
        }, { passive: true });
    }

    // ---- Staggered Label Animation ----
    const serviceCards = document.querySelectorAll('.dienst-card');
    serviceCards.forEach(card => {
        const labels = card.querySelectorAll('.label');
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    labels.forEach((label, i) => {
                        label.style.opacity = '0';
                        label.style.transform = 'translateY(8px) scale(0.95)';
                        setTimeout(() => {
                            label.style.transition = `all 0.5s cubic-bezier(0.16, 1, 0.3, 1) ${i * 0.06}s`;
                            label.style.opacity = '1';
                            label.style.transform = 'translateY(0) scale(1)';
                        }, 200);
                    });
                    cardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        cardObserver.observe(card);
    });

    // ---- Stat Counter Animation ----
    const stats = document.querySelectorAll('.stat__number');
    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const raw = el.textContent.trim();
                const suffix = raw.replace(/[\d.]/g, '');
                const target = parseInt(raw);
                
                if (!isNaN(target)) {
                    let current = 0;
                    const duration = 1500;
                    const step = target / (duration / 16);
                    
                    const counter = () => {
                        current += step;
                        if (current < target) {
                            el.textContent = Math.floor(current) + suffix;
                            requestAnimationFrame(counter);
                        } else {
                            el.textContent = target + suffix;
                        }
                    };
                    counter();
                }
                statObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    
    stats.forEach(s => statObserver.observe(s));

    // ---- Glass Shimmer on Nav Hover ----
    const navPill = document.querySelector('.nav__pill');
    if (navPill) {
        navPill.addEventListener('mouseenter', () => {
            navPill.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
        });
    }

    // ---- Parallax on Dienst Card Images ----
    if (window.innerWidth > 768) {
        const dienstImages = document.querySelectorAll('.dienst-card__image img');
        window.addEventListener('scroll', () => {
            dienstImages.forEach(img => {
                const rect = img.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
                if (isVisible) {
                    const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                    const translate = (progress - 0.5) * 15;
                    img.style.transform = `scale(1.05) translateY(${translate}px)`;
                }
            });
        }, { passive: true });
    }

    // ---- Team Photo Parallax ----
    if (window.innerWidth > 768) {
        const teamPhotos = document.querySelectorAll('.team__photo img');
        window.addEventListener('scroll', () => {
            teamPhotos.forEach(img => {
                const rect = img.parentElement.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
                if (isVisible) {
                    const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                    const translate = (progress - 0.5) * 20;
                    img.style.transform = `scale(1.05) translateY(${translate}px)`;
                }
            });
        }, { passive: true });
    }
});

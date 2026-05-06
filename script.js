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

    // Removed Hero Tags toggle logic since tags are now static anchor links

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



    let currentSlideLabel = '';
    const navLinks = document.querySelectorAll('.nav__links a');
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

                if (activeLabel) {
                    currentSlideLabel = activeLabel;
                }

                // Update Mobile Tracker Label only if menus are not open
                const isMenuOpen = nav.className.includes('-open') || (mobileMenu && mobileMenu.classList.contains('active'));
                
                if (!isMenuOpen) {
                    if (activeLabel && activeLabel !== 'Home') {
                        nav.classList.add('nav--state-active');
                        transitionStateText(activeLabel);
                    } else {
                        nav.classList.remove('nav--state-active');
                    }
                }

                // Update Desktop Links highlighting (yellow color)
                navLinks.forEach(link => {
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
            const isActive = !mobileMenu.classList.contains('active');
            hamburger.classList.toggle('active', isActive);
            mobileMenu.classList.toggle('active', isActive);
            document.body.style.overflow = isActive ? 'hidden' : '';

            if (isActive) {
                nav.classList.add('nav--state-active');
                transitionStateText('Menu');
            } else {
                if (currentSlideLabel && currentSlideLabel !== 'Home') {
                    nav.classList.add('nav--state-active');
                    transitionStateText(currentSlideLabel);
                } else {
                    nav.classList.remove('nav--state-active');
                    transitionStateText('');
                }
            }
        });
    }

    
    // Team Bios Overlay Logic
    const teamCardsInteractive = Array.from(document.querySelectorAll(".team-card--photo, .team-card--purple.team-card--interactive"));
    const mobileModal = document.getElementById("mobile-bio-modal");
    const mobileModalScroll = mobileModal ? mobileModal.querySelector(".team-bios-modal__scroll") : null;
    const mobileModalDots = mobileModal ? mobileModal.querySelectorAll(".team-slider__dot") : [];
    const mobileModalClose = mobileModal ? mobileModal.querySelector(".team-bios-modal__close") : null;
    
    let isModalPopulated = false;

    // Helper: Populeer mobiele modal
    function populateMobileModal() {
        if (isModalPopulated || !mobileModalScroll) return;
        
        mobileModalScroll.innerHTML = ""; // Clear
        
        teamCardsInteractive.forEach((card, i) => {
            // Data extracting
            const isPurple = card.classList.contains("team-card--purple");
            const bioOverlay = card.querySelector(".team-card__bio-overlay");
            const avatarSrc = bioOverlay.querySelector(".team-card__bio-avatar")?.src || "assets/Jappy%20meer%20info.webp";
            const title = bioOverlay.querySelector("h3")?.innerText || "";
            const role = bioOverlay.querySelector(".team-card__bio-role")?.innerText || "";
            const headline = bioOverlay.querySelector(".team-card__bio-headline")?.innerText || "";
            const paragraphs = Array.from(bioOverlay.querySelectorAll("p, .team-bios-modal__image-wrapper")).map(el => {
                if (el.classList.contains('team-bios-modal__image-wrapper')) return el.outerHTML;
                return `<p>${el.innerHTML}</p>`;
            }).join("");
            const contentTags = card.querySelector(".team-card__tags");
            
            let tagsArray = [];
            if (contentTags) {
                const tags = Array.from(contentTags.querySelectorAll(".team-card__tag:not(.team-card__info-btn)"));
                tagsArray = tags.map(t => t.innerText);
            } else if (isPurple) {
                tagsArray = ["Development", "Strategie", "Fotografie"];
            }
            let tagsHtml = "";
            if (tagsArray.length > 0) {
                tagsHtml = `<div class="team-bios-modal__tags-top"><span>${tagsArray.join(" &bull; ")}</span></div>`;
            }

            const avatarHtml = isPurple ? `
                <div class="team-bios-modal__header-avatars">
                    <img src="assets/Vriend%20van%20Grut_%20Wypkje.webp" class="team-bios-modal__header-avatar" alt="Wypkje" loading="lazy">
                    <img src="assets/Vriend%20van%20Grut_%20Marc.webp" class="team-bios-modal__header-avatar" alt="Marc" loading="lazy">
                    <img src="assets/Vriend%20van%20Grut_%20Chris.webp" class="team-bios-modal__header-avatar" alt="Chris" loading="lazy">
                </div>
            ` : `<img src="${avatarSrc}" class="team-bios-modal__header-avatar" alt="${title}">`;

            // CTA Footer
            const mailNaam = title.split(' ')[0] || 'ons';
            const ctaText = isPurple ? "Samenwerken?" : `Contact ${mailNaam}`;
            const footerHtml = `
                <div class="team-bios-modal__footer">
                    <button class="team-bios-modal__nav-btn team-slider-prev-btn" aria-label="Vorige">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <a href="mailto:letsgo@grutdesigners.nl" class="team-bios-modal__btn-primary">
                        ${ctaText}
                    </a>
                    <button class="team-bios-modal__nav-btn team-slider-next-btn" aria-label="Volgende">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            `;


            const slideHtml = `
                <div class="team-bios-modal__slide">
                    <div class="team-bios-modal__header">
                        <div class="team-bios-modal__header-left">
                            ${avatarHtml}
                            <div class="team-bios-modal__title-block">
                                <h3>${title}</h3>
                                <span>${role}</span>
                            </div>
                        </div>
                        <button class="team-bios-modal__header-close team-slider-close-btn" aria-label="Sluit informatie">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="team-bios-modal__body">
                        ${tagsHtml}
                        <h1 class="team-bios-modal__headline">${headline}</h1>
                        <div class="team-bios-modal__content">${paragraphs}</div>
                    </div>
                    ${footerHtml}
                </div>
            `;
            mobileModalScroll.insertAdjacentHTML("beforeend", slideHtml);
        });
        
        isModalPopulated = true;
    }

    teamCardsInteractive.forEach((card, index) => {
        card.addEventListener("click", (e) => {
            // Stop close button from propagating to card click
            if (e.target.closest(".team-card__bio-close")) return;
            
            if (window.innerWidth <= 768) {
                // Mobile Modal Routine
                populateMobileModal();
                mobileModal.classList.add("is-active");
                document.body.classList.add("no-scroll");
                
                // Jump to index without smooth scroll first
                setTimeout(() => {
                    const slideWidth = mobileModalScroll.clientWidth;
                    mobileModalScroll.scrollTo({ left: index * slideWidth, behavior: "instant" });
                    updateModalDots();
                }, 10);
            } else {
                // Desktop Routine: accordion unfold within card
                if (!card.classList.contains("is-open")) {
                    document.querySelectorAll(".team-card.is-open").forEach(openCard => {
                        openCard.classList.remove("is-open");
                    });
                    card.classList.add("is-open");
                }
            }
        });
    });

    // Delegate Desktop inline bio close
    document.addEventListener("click", (e) => {
        const desktopCloseBtn = e.target.closest(".team-card__bio-close");
        if (desktopCloseBtn && window.innerWidth > 768) {
            const card = desktopCloseBtn.closest(".team-card");
            if (card) card.classList.remove("is-open");
            e.stopPropagation();
        }
    });

    if (mobileModal) {
        // Mobile Modal Slider Logic & Close
        if (mobileModalClose) {
            mobileModalClose.addEventListener("click", () => {
                mobileModal.classList.remove("is-active");
                document.body.classList.remove("no-scroll");
            });
        }
        
        // Prevent scroll-bleed closing when clicking background and handle dynamic buttons
        mobileModal.addEventListener("click", (e) => {
            if (e.target === mobileModal) {
                mobileModal.classList.remove("is-active");
                document.body.classList.remove("no-scroll");
            }
            
            // Delegate Close
            const closeBtn = e.target.closest(".team-slider-close-btn");
            if (closeBtn) {
                mobileModal.classList.remove("is-active");
                document.body.classList.remove("no-scroll");
            }
            
            // Delegate Next
            const nextBtn = e.target.closest(".team-slider-next-btn");
            if (nextBtn) {
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let nextIndex = currentIndex + 1;
                if (nextIndex >= teamCardsInteractive.length) nextIndex = 0;
                mobileModalScroll.scrollTo({ left: nextIndex * cardWidth, behavior: "smooth" });
            }
            
            // Delegate Prev
            const prevBtn = e.target.closest(".team-slider-prev-btn");
            if (prevBtn) {
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let prevIndex = currentIndex - 1;
                if (prevIndex < 0) prevIndex = teamCardsInteractive.length - 1;
                mobileModalScroll.scrollTo({ left: prevIndex * cardWidth, behavior: "smooth" });
            }
        });

        window.updateModalDots = function() {
            const scrollLeft = mobileModalScroll.scrollLeft;
            const cardWidth = mobileModalScroll.clientWidth;
            let targetIndex = Math.round(scrollLeft / cardWidth);
            if (targetIndex < 0) targetIndex = 0;
            if (targetIndex > 2) targetIndex = 2;
            
            mobileModalDots.forEach(d => d.classList.remove("active"));
            if (mobileModalDots[targetIndex]) mobileModalDots[targetIndex].classList.add("active");
        };

        mobileModalScroll.addEventListener("scroll", updateModalDots, { passive: true });
        
        mobileModalDots.forEach((dot, idx) => {
            dot.addEventListener("click", () => {
                const cardWidth = mobileModalScroll.clientWidth;
                mobileModalScroll.scrollTo({ left: idx * cardWidth, behavior: "smooth" });
            });
        });
    }

});

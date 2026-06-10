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
        let ticking = false;
        slidesContainer.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (slidesContainer.scrollTop > 80) {
                        nav.classList.add('nav--scrolled');
                    } else {
                        nav.classList.remove('nav--scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
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

    // ---- Footer Scroll-to-top button ----
    const footerScrollBtn = document.querySelector('.footer-scroll-top');
    if (footerScrollBtn && slidesContainer) {
        footerScrollBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (typeof closePanel === 'function') closePanel();
            slidesContainer.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---- Copy to clipboard buttons ----
    const copyBtns = document.querySelectorAll('.copy-btn');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const textToCopy = btn.getAttribute('data-copy');
            if (!textToCopy) return;

            const iconContainer = btn.querySelector('.copy-icon-wrapper') || btn;

            const onSuccess = () => {
                const originalHTML = iconContainer.innerHTML;
                btn.classList.add('copied');
                iconContainer.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="footer-cta__icon"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
                setTimeout(() => {
                    btn.classList.remove('copied');
                    iconContainer.innerHTML = originalHTML;
                }, 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(onSuccess).catch(err => console.error('Copy failed', err));
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = textToCopy;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    onSuccess();
                } catch (err) {
                    console.error('Fallback copy failed', err);
                }
                textArea.remove();
            }
        });
    });

    let currentSlideLabel = '';
    const navLinks = document.querySelectorAll('.nav__links a');
    const slideObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                let activeLabel = '';
                let activeLinkId = '';
                const slideMap = {
                    'hero': { label: 'Home' },
                    'over-ons-bubbels': { label: 'Over ons', linkId: 'navOverOnsLink' },
                    'missie': { label: 'Over ons', linkId: 'navOverOnsLink' },
                    'over-ons': { label: 'Over ons', linkId: 'navOverOnsLink' },
                    'diensten': { label: 'Diensten', linkId: 'navDienstenLink' },
                    'portfolio': { label: 'Portfolio', linkId: 'navPortfolioLink' },
                    'team': { label: 'Team', linkId: 'navTeamLink' },
                    'contact': { label: 'Contact' }
                };

                for (const key in slideMap) {
                    if (id.includes(key)) {
                        activeLabel = slideMap[key].label;
                        activeLinkId = slideMap[key].linkId || '';
                        break;
                    }
                }

                if (activeLabel) currentSlideLabel = activeLabel;

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
    const interactiveCards = Array.from(document.querySelectorAll(".card")).filter(card => card.querySelector(".card__modal-template"));
    const mobileModal = document.getElementById("mobile-bio-modal");
    const mobileModalScroll = mobileModal ? mobileModal.querySelector(".card-modal__scroll") : null;
    const mobileModalDots = mobileModal ? mobileModal.querySelectorAll(".card-slider__dot") : [];
    const mobileModalClose = mobileModal ? mobileModal.querySelector(".card-modal__close") : null;
    
    let isModalPopulated = false;

    // Helper: Build Card Modal
    function buildCardModal() {
        if (isModalPopulated || !mobileModalScroll) return;
        
        mobileModalScroll.innerHTML = ""; // Clear
        
        interactiveCards.forEach((card, i) => {
            // Data extracting - support both DOM elements and data attributes
            const isPurple = card.classList.contains("card--primary") || card.classList.contains("card--purple");
            const template = card.querySelector(".card__modal-template");
            
            // Extract meta (prefer DOM, fallback to dataset)
            const title = (template?.querySelector("h3")?.innerText) || card.dataset.modalTitle || "";
            const role = (template?.querySelector(".card-modal__role")?.innerText) || card.dataset.modalRole || "";
            const avatarSrc = (template?.querySelector(".card-modal__avatar")?.src) || card.dataset.modalAvatar || "";
            const headline = (template?.querySelector(".card-modal__headline")?.innerText) || card.dataset.modalHeadline || "";
            
            // Extract complex content
            let contentHtml = "";
            if (template) {
                // If the user provided a dedicated content slot, use it. Otherwise, fallback to scraping p and image wrappers.
                const slot = template.querySelector(".card-modal__content-slot");
                if (slot) {
                    contentHtml = slot.innerHTML;
                } else {
                    contentHtml = Array.from(template.querySelectorAll("p, .card-modal__image-wrapper")).map(el => {
                        if (el.classList.contains('card-modal__image-wrapper')) return el.outerHTML;
                        return `<p>${el.innerHTML}</p>`;
                    }).join("");
                }
            }

            const contentTags = card.querySelector(".card__tags");
            let tagsArray = [];
            if (contentTags) {
                const tags = Array.from(contentTags.querySelectorAll(".card__tag:not(.card__info-btn)"));
                tagsArray = tags.map(t => t.innerText);
            } else if (card.dataset.modalTags) {
                tagsArray = card.dataset.modalTags.split(",").map(t => t.trim());
            } else if (isPurple) {
                tagsArray = ["Development", "Strategie", "Fotografie"];
            }
            
            let tagsHtml = "";
            if (tagsArray.length > 0) {
                tagsHtml = `<div class="card-modal__tags-top"><span>${tagsArray.join(" &bull; ")}</span></div>`;
            }

            let avatarHtml = "";
            if (isPurple || card.dataset.modalAvatars) {
                // Network cards with multiple avatars
                avatarHtml = `
                <div class="card-modal__header-avatars">
                    <img src="assets/Vriend%20van%20Grut_%20Wypkje.webp" class="card-modal__header-avatar" alt="Wypkje" loading="lazy">
                    <img src="assets/Vriend%20van%20Grut_%20Marc.webp" class="card-modal__header-avatar" alt="Marc" loading="lazy">
                    <img src="assets/Vriend%20van%20Grut_%20Chris.webp" class="card-modal__header-avatar" alt="Chris" loading="lazy">
                </div>`;
            } else if (avatarSrc) {
                avatarHtml = `<img src="${avatarSrc}" class="card-modal__header-avatar" alt="${title}">`;
            }

            // CTA Footer
            const mailNaam = title.split(' ')[0] || 'ons';
            const defaultCta = isPurple ? "Samenwerken?" : `Contact ${mailNaam}`;
            const ctaText = card.dataset.modalCta || defaultCta;
            const footerHtml = `
                <div class="card-modal__footer">
                    <button class="card-modal__nav-btn card-slider-prev-btn" aria-label="Vorige">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <a href="mailto:letsgo@grutdesigners.nl" class="card-modal__btn-primary">
                        ${ctaText}
                    </a>
                    <button class="card-modal__nav-btn card-slider-next-btn" aria-label="Volgende">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            `;


            const slideHtml = `
                <div class="card-modal__slide">
                    <div class="card-modal__header">
                        <div class="card-modal__header-left">
                            ${avatarHtml}
                            <div class="card-modal__title-block">
                                <h3>${title}</h3>
                                <span>${role}</span>
                            </div>
                        </div>
                        <button class="card-modal__header-close card-slider-close-btn" aria-label="Sluit informatie">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="card-modal__body">
                        ${tagsHtml}
                        <h1 class="card-modal__headline">${headline}</h1>
                        <div class="card-modal__content">${contentHtml}</div>
                    </div>
                    ${footerHtml}
                </div>
            `;
            mobileModalScroll.insertAdjacentHTML("beforeend", slideHtml);
            
            // Add scroll listener for desktop "liquid glass" interaction
            const newSlide = mobileModalScroll.lastElementChild;
            const newHeader = newSlide.querySelector('.card-modal__header');
            const avatar = newHeader.querySelector('.card-modal__header-avatar:not(.card-modal__header-avatars .card-modal__header-avatar)');
            const avatars = newHeader.querySelector('.card-modal__header-avatars');
            const closeBtn = newHeader.querySelector('.card-modal__header-close');
            const titleBlock = newHeader.querySelector('.card-modal__title-block');
            
            let isScrolled = false;
            
            newSlide.addEventListener('scroll', () => {
                if (window.innerWidth < 769) return;
                
                const currentScroll = newSlide.scrollTop;
                if (currentScroll > 40 && !isScrolled) {
                    isScrolled = true;
                    newHeader.classList.add('is-scrolled');
                    
                    // The inner text width = titleBlock.offsetWidth without padding.
                    // We know base padding is 0 2rem (36px * 2 = 72px)
                    const textWidth = titleBlock.offsetWidth - 72;
                    // Expanded padding is 0 4.5rem (81px * 2 = 162px).
                    const expandedTitleWidth = textWidth + 162;
                    
                    // 12px inset from the padding edge
                    const avatarLeft = (newHeader.offsetWidth / 2) - (expandedTitleWidth / 2) + 12;
                    const closeRight = (newHeader.offsetWidth / 2) - (expandedTitleWidth / 2) + 12;
                    
                    if (avatar) avatar.style.left = avatarLeft + 'px';
                    if (avatars) avatars.style.left = avatarLeft + 'px';
                    if (closeBtn) closeBtn.style.right = closeRight + 'px';
                    
                } else if (currentScroll <= 40 && isScrolled) {
                    isScrolled = false;
                    newHeader.classList.remove('is-scrolled');
                    
                    if (avatar) avatar.style.left = '';
                    if (avatars) avatars.style.left = '';
                    if (closeBtn) closeBtn.style.right = '';
                }
            }, { passive: true });
        });
        
        // Dynamically generate dots
        const dotsContainer = mobileModal.querySelector(".card-slider__dots");
        if (dotsContainer) {
            dotsContainer.innerHTML = interactiveCards.map((_, idx) => 
                `<div class="card-slider__dot ${idx === 0 ? 'active' : ''}" data-index="${idx}"></div>`
            ).join("");
            
            // Re-bind dots
            const newDots = dotsContainer.querySelectorAll(".card-slider__dot");
            newDots.forEach((dot, idx) => {
                dot.addEventListener("click", () => {
                    const cardWidth = mobileModalScroll.clientWidth;
                    mobileModalScroll.scrollTo({ left: idx * cardWidth, behavior: "smooth" });
                });
            });
        }
        
        isModalPopulated = true;
    }

    interactiveCards.forEach((card, index) => {
        card.addEventListener("click", (e) => {
            // Stop close button from propagating to card click
            if (e.target.closest(".card-modal__close-btn")) return;
            
            // Modal Routine (now for both mobile and desktop)
            buildCardModal();
            mobileModal.classList.add("is-active");
            document.body.classList.add("no-scroll");
            
            // Jump to index without smooth scroll first
            setTimeout(() => {
                const slideWidth = mobileModalScroll.clientWidth;
                mobileModalScroll.scrollTo({ left: index * slideWidth, behavior: "instant" });
                updateModalDots();
            }, 10);
        });
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
            // Close if clicking outside the content (header, body, footer)
            if (e.target === mobileModal || 
                e.target.classList.contains("card-modal__scroll") || 
                e.target.classList.contains("card-modal__slide")) {
                
                // Extra check to ensure we didn't click inside the content areas
                if (!e.target.closest(".card-modal__header") && 
                    !e.target.closest(".card-modal__body") && 
                    !e.target.closest(".card-modal__footer")) {
                    mobileModal.classList.remove("is-active");
                    document.body.classList.remove("no-scroll");
                }
            }
            
            // Delegate Close
            const closeBtn = e.target.closest(".card-slider-close-btn");
            if (closeBtn) {
                mobileModal.classList.remove("is-active");
                document.body.classList.remove("no-scroll");
            }
            
            // Delegate Next
            const nextBtn = e.target.closest(".card-slider-next-btn");
            if (nextBtn) {
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let nextIndex = currentIndex + 1;
                if (nextIndex >= interactiveCards.length) nextIndex = 0;
                mobileModalScroll.scrollTo({ left: nextIndex * cardWidth, behavior: "smooth" });
            }
            
            // Delegate Prev
            const prevBtn = e.target.closest(".card-slider-prev-btn");
            if (prevBtn) {
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let prevIndex = currentIndex - 1;
                if (prevIndex < 0) prevIndex = interactiveCards.length - 1;
                mobileModalScroll.scrollTo({ left: prevIndex * cardWidth, behavior: "smooth" });
            }
        });

        window.updateModalDots = function() {
            const scrollLeft = mobileModalScroll.scrollLeft;
            const cardWidth = mobileModalScroll.clientWidth;
            let targetIndex = Math.round(scrollLeft / cardWidth);
            if (targetIndex < 0) targetIndex = 0;
            if (targetIndex > interactiveCards.length - 1) targetIndex = interactiveCards.length - 1;
            
            const freshDots = mobileModal.querySelectorAll(".card-slider__dot");
            freshDots.forEach(d => d.classList.remove("active"));
            if (freshDots[targetIndex]) freshDots[targetIndex].classList.add("active");
        };

        let modalTicking = false;
        mobileModalScroll.addEventListener("scroll", () => {
            if (!modalTicking) {
                window.requestAnimationFrame(() => {
                    updateModalDots();
                    modalTicking = false;
                });
                modalTicking = true;
            }
        }, { passive: true });
    }

    // ---- Lazy Load Heavy GIFs for Hero Tags ----
    // We delay loading the large ~15MB of gifs so they don't block the LCP
    setTimeout(() => {
        document.querySelectorAll('.hero__tag--gif-hover').forEach(tag => {
            const gifUrl = tag.getAttribute('data-hover-gif');
            if (gifUrl) {
                tag.style.setProperty('--hover-gif', gifUrl);
            }
        });
    }, 2500);

    // ---- FAQ Accordion Logic ----
    const faqContainer = document.querySelector('.faq-container');
    const faqItems = document.querySelectorAll('.faq-item');

    if (faqContainer && faqItems.length > 0) {
        faqItems.forEach(item => {
            const header = item.querySelector('.faq-item__header');
            if (header) {
                header.addEventListener('click', () => {
                    const isActive = item.classList.contains('is-active');
                    
                    // Close all items
                    faqItems.forEach(i => i.classList.remove('is-active'));
                    
                    if (!isActive) {
                        // Open clicked item
                        item.classList.add('is-active');
                        faqContainer.classList.add('has-active');
                    } else {
                        // All closed
                        faqContainer.classList.remove('has-active');
                    }
                });
            }
        });
    }

    // ---- Drag to Scroll Logic for Horizontal Sliders (Desktop) ----
    const scrollContainers = document.querySelectorAll('.cards-scroll');
    
    scrollContainers.forEach(slider => {
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('is-dragging');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('is-dragging');
        });
        
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('is-dragging');
        });
        
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5; // Drag speed multiplier
            slider.scrollLeft = scrollLeft - walk;
        });
    });

    // =========================================
    // VENN DIAGRAM ANIMATION
    // =========================================
    const vennBubbleA = document.getElementById('venn-bubble-a');
    const vennBubbleB = document.getElementById('venn-bubble-b');
    const vennTextA = document.getElementById('venn-text-a');
    const vennTextB = document.getElementById('venn-text-b');
    const vennLogo = document.querySelector('.venn-logo-container');

    const wordsA = ['Digitale<br>ambities', 'Onrust', 'Complexe<br>vraagstukken', 'Afstand', 'Systeem', 'Techniek'];
    const wordsB = ['Optimale<br>beleving', 'Vertrouwen', 'Eenvoud', 'Verbinding', 'Mens', 'Emotie'];
    
    let indexA = 0;
    let indexB = 0;

    function animateVennBubble(bubble, textElement, wordsArray, indexVar) {
        if (!bubble || !textElement || !vennLogo) return indexVar;

        // Scale up
        bubble.classList.add('scale-up');
        vennLogo.classList.add('scale-up');
        
        const content = bubble.querySelector('.venn-bubble-content');
        if (content) {
            content.style.opacity = '0';
        }

        setTimeout(() => {
            // Update text
            indexVar = (indexVar + 1) % wordsArray.length;
            textElement.innerHTML = wordsArray[indexVar];
            
            // Fade back in
            if (content) {
                content.style.opacity = '1';
            }
            
            // Scale down
            bubble.classList.remove('scale-up');
            vennLogo.classList.remove('scale-up');
        }, 200);

        return indexVar;
    }

    if (vennBubbleA && vennTextA) {
        vennBubbleA.addEventListener('click', () => {
            indexA = animateVennBubble(vennBubbleA, vennTextA, wordsA, indexA);
        });
    }

    if (vennBubbleB && vennTextB) {
        vennBubbleB.addEventListener('click', () => {
            indexB = animateVennBubble(vennBubbleB, vennTextB, wordsB, indexB);
        });
    }

});

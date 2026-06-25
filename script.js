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

    // ---- Logo Scroll Animation (wordmark → beeldmerk) ----
    let arrowTimeout;
    if (slidesContainer && nav) {
        let ticking = false;
        slidesContainer.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (slidesContainer.scrollTop > 80) {
                        if (!nav.classList.contains('nav--scrolled')) {
                            nav.classList.add('nav--scrolled');
                            if (navLogo) {
                                clearTimeout(arrowTimeout);
                                arrowTimeout = setTimeout(() => {
                                    navLogo.classList.add('show-arrow');
                                    setTimeout(() => {
                                        navLogo.classList.remove('show-arrow');
                                    }, 3000);
                                }, 10000);
                            }
                        }
                    } else {
                        if (nav.classList.contains('nav--scrolled')) {
                            nav.classList.remove('nav--scrolled');
                            if (navLogo) {
                                clearTimeout(arrowTimeout);
                                navLogo.classList.remove('show-arrow');
                            }
                        }
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
                nav.classList.remove('nav--contact-open');
                slidesContainer.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                e.preventDefault();
            }
        });
    }

    let currentSlideLabel = '';

    function updateMobileLabel(newLabelText, scrollingDown = true, delay = 0) {
        const navMobileLabelCenter = document.getElementById('navMobileLabelCenter');
        const navMobileLabelTop = document.getElementById('navMobileLabelTop');
        const navMobileLabelBottom = document.getElementById('navMobileLabelBottom');
        const navMobileLabelSlider = document.getElementById('navMobileLabelSlider');

        // Ensure initial color is set
        if (navMobileLabelCenter && !navMobileLabelCenter.style.color) {
            const isWhiteInit = navMobileLabelCenter.textContent.includes('Hoi') || navMobileLabelCenter.textContent.includes('Menu');
            navMobileLabelCenter.style.color = isWhiteInit ? 'var(--color-cream)' : 'var(--color-yellow)';
        }

        if (navMobileLabelCenter && navMobileLabelTop && navMobileLabelBottom && navMobileLabelSlider) {
            if (navMobileLabelCenter.textContent !== newLabelText && window.navLabelTimeoutText !== newLabelText) {
                clearTimeout(window.navLabelTimeout);
                window.navLabelTimeoutText = newLabelText;
                const isWhite = newLabelText.includes('Hoi') || newLabelText.includes('Menu');
                const newColor = isWhite ? 'var(--color-cream)' : 'var(--color-yellow)';
                
                window.navLabelTimeout = setTimeout(() => {
                    if (scrollingDown) {
                        navMobileLabelBottom.textContent = newLabelText;
                        navMobileLabelBottom.style.color = newColor;
                        navMobileLabelSlider.style.transition = 'transform 0.25s cubic-bezier(0.65, 0, 0.35, 1)';
                        navMobileLabelSlider.style.transform = 'translateY(-66.666%)';
                    } else {
                        navMobileLabelTop.textContent = newLabelText;
                        navMobileLabelTop.style.color = newColor;
                        navMobileLabelSlider.style.transition = 'transform 0.25s cubic-bezier(0.65, 0, 0.35, 1)';
                        navMobileLabelSlider.style.transform = 'translateY(0%)';
                    }
                    
                    setTimeout(() => {
                        navMobileLabelSlider.style.transition = 'none';
                        navMobileLabelCenter.textContent = newLabelText;
                        navMobileLabelCenter.style.color = newColor;
                        navMobileLabelSlider.style.transform = 'translateY(-33.333%)';
                        navMobileLabelTop.textContent = '';
                        navMobileLabelBottom.textContent = '';
                    }, 250);
                }, delay);
            }
        }
    }
    const navLinks = document.querySelectorAll('.nav__links a');

    const navCtaBtn = document.getElementById('navCtaBtn');
    const navContactClose = document.getElementById('navContactClose');
    const mobMenuClose = document.getElementById('mobMenuClose');

    const slideObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-active-slide');
                const id = entry.target.id;
                let activeLabel = '';
                let activeLinkId = '';
                const slideMap = {
                    'hero': { label: 'Home' },
                    'missie-copy': { label: 'Over ons', linkId: 'navOverOnsLink' },
                    'diensten': { label: 'Diensten', linkId: 'navAanpakLink' },
                    'over-ons-venn-kopie': { label: 'De brug', linkId: 'navAanpakLink' },
                    'over-ons-radar': { label: 'Aanpak', linkId: 'navRadarLink' },
                    'portfolio': { label: 'Werk', linkId: 'navPortfolioLink' },
                    'over-ons-aanpak': { label: 'Team', linkId: 'navTeamLink' },
                    'faq': { label: 'Aanpak', linkId: 'navRadarLink' },
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

                // Update Mobile Label
                const newLabelText = currentSlideLabel === 'Home' ? 'Hoi 👋' : currentSlideLabel;
                
                const allSlides = Array.from(document.querySelectorAll('.slide'));
                const newSlideIndex = allSlides.indexOf(entry.target);
                const oldSlideIndex = window.navLabelCurrentIndex !== undefined ? window.navLabelCurrentIndex : 0;
                const scrollingDown = newSlideIndex > oldSlideIndex;
                window.navLabelCurrentIndex = newSlideIndex;
                
                // Only auto-update if the mobile menu is NOT open
                const mobileMenu = document.getElementById('mobileMenu');
                if (!mobileMenu || !mobileMenu.classList.contains('active')) {
                    updateMobileLabel(newLabelText, scrollingDown, 500);
                }

                // Update Desktop Links highlighting (yellow color)
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
                if (activeLinkId) {
                    const link = document.getElementById(activeLinkId);
                    if (link) link.classList.add('active');
                }
            } else {
                entry.target.classList.remove('is-active-slide');
            }
        });
    }, {
        root: slidesContainer,
        rootMargin: "-30% 0px -30% 0px",
        threshold: 0 // trigger whenever any part of the slide is in the middle 40% of the screen
    });

    sections.forEach(slide => slideObserver.observe(slide));

    // ---- Navigation Contact Open State ----
    if (navCtaBtn) {
        navCtaBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nav.classList.add('nav--contact-open');
        });
    }

    if (navContactClose) {
        navContactClose.addEventListener('click', (e) => {
            e.preventDefault();
            nav.classList.remove('nav--contact-open');
        });
    }

    // ---- Smooth Scroll for Anchor Links (Magnetism Support) ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (href === '#') return; // Ignore empty hashes
            
            const target = document.querySelector(href);
            if (!target || !slidesContainer) return;

            e.preventDefault();
            
            // Close nav states if open
            nav.classList.remove('nav--contact-open');
            if (hamburger) hamburger.classList.remove('active');
            if (mobileMenu) mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
            
            target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    function animateCopyText(btn, newText) {
        const span = btn.querySelector('span');
        if (!span || span.textContent === newText) return;
        const origText = span.textContent;
        span.textContent = newText;
        // Optionally add a class for styling
        setTimeout(() => {
            span.textContent = origText;
        }, 1600);
    }

    // ---- Connect Copy Buttons ----
    const copyTargets = [
        ['navCopyEmail', 'mobCopyEmail', 'info@grutdesigners.nl']
    ];

    copyTargets.forEach(([navId, mobId, text]) => {
        [document.getElementById(navId), document.getElementById(mobId)].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => animateCopyText(btn, 'Gekopieerd!'));
                    } else {
                        const textArea = document.createElement("textarea");
                        textArea.value = text;
                        textArea.style.position = "fixed";
                        textArea.style.left = "-999999px";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            animateCopyText(btn, 'Gekopieerd!');
                        } catch (err) {}
                        textArea.remove();
                    }
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
                updateMobileLabel('Menu 👇', true, 0);
            } else {
                updateMobileLabel(currentSlideLabel === 'Home' ? 'Hoi 👋' : currentSlideLabel, false, 0);
            }
        });
    }

    if (mobMenuClose) {
        mobMenuClose.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
            updateMobileLabel(currentSlideLabel === 'Home' ? 'Hoi 👋' : currentSlideLabel, false, 0);
        });
    }

    // Team Bios Overlay Logic
    const interactiveCards = Array.from(document.querySelectorAll(".card")).filter(card => card.querySelector(".card-overlay-content") || card.querySelector(".card__modal-template"));
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
            // Theme extraction
            const template = card.querySelector(".card__modal-template");
            
            // Extract meta
            const title = card.dataset.modalTitle || (template?.querySelector("h3")?.innerText) || "";
            const role = card.dataset.modalRole || (template?.querySelector(".card-modal__role")?.innerText) || "";
            const headline = (template?.querySelector(".card-modal__headline")?.innerText) || card.dataset.modalHeadline || "";
            const avatarSrc = card.dataset.modalAvatar || (template?.querySelector(".card-modal__avatar")?.src) || "";
            const imageSrc = card.dataset.modalImage || (template?.querySelector(".card-modal__image")?.src) || "";
            
            // Tags extraction
            const contentTags = card.querySelector(".card__tags");
            let tagsArray = [];
            if (contentTags) {
                const tags = Array.from(contentTags.querySelectorAll(".card__tag:not(.card__info-btn), .card__tag-pill"));
                tagsArray = tags.map(t => t.innerText);
            } else if (card.dataset.modalTags) {
                tagsArray = card.dataset.modalTags.split(",").map(t => t.trim());
            } else {
                 const cardTitleText = card.querySelector(".card__title") ? card.querySelector(".card__title").innerText : "";
                 if (cardTitleText) tagsArray = [cardTitleText];
            }
            
            // Add name/role to tags if they exist (e.g., "Jappy")
            if (role && !tagsArray.includes(role)) {
                tagsArray.push(role);
            } else if (title && !headline && !tagsArray.includes(title)) {
                // If title is a person's name (like Jappy)
                // We'll just push it if it's short
                if (title.length < 15 && !tagsArray.includes(title)) tagsArray.push(title);
            }

            let tagsHtml = "";
            if (tagsArray.length > 0) {
                tagsHtml = tagsArray.map(tag => `<span class="overlay-header__tag">${tag}</span>`).join("");
            }
            
            // Content
            let contentHtml = "";
            const contentTemplate = card.querySelector(".card-overlay-content");
            if (contentTemplate) {
                contentHtml = contentTemplate.innerHTML;
            } else {
                contentHtml = "<p>Details komen binnenkort.</p>";
            }
            
            // Clean up contentHtml: Remove headline if it's already extracted to avoid duplication
            if (headline) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = contentHtml;
                const hl = tempDiv.querySelector('.card-modal__headline');
                if (hl && hl.innerText === headline) {
                    hl.remove();
                }
                contentHtml = tempDiv.innerHTML;
            }
            
            const displayTitle = headline || title || "Grut.";

            // Construct the slide
            const slideHtml = `
                <div class="card-modal__slide">
                    <div class="overlay-header">
                        <div class="overlay-header__tags">
                            ${tagsHtml}
                        </div>
                        <button class="overlay-header__close card-slider-close-btn" aria-label="Sluiten">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="overlay-content-container">
                        <h3 class="overlay-title">${displayTitle}</h3>
                        <div class="overlay-flexible-content">
                            ${contentHtml}
                        </div>
                        
                        <div class="overlay-footer">
                            <button class="overlay-footer-btn overlay-footer-btn--primary card-slider-close-btn">Sluiten</button>
                            <button class="overlay-footer-btn overlay-footer-btn--secondary card-slider-next-btn">Volgende</button>
                        </div>
                    </div>
                </div>
            `;
            mobileModalScroll.insertAdjacentHTML("beforeend", slideHtml);
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

    window.openMainOverlay = function(index) {
        buildCardModal();
        mobileModal.classList.add("is-active");
        document.body.classList.add("no-scroll");
        
        // Ensure all slides start at the top when opening
        mobileModal.querySelectorAll('.card-modal__slide').forEach(slide => {
            slide.scrollTop = 0;
        });
        
        // Jump to index without smooth scroll first
        setTimeout(() => {
            const slideWidth = mobileModalScroll.clientWidth;
            mobileModalScroll.scrollTo({ left: index * slideWidth, behavior: "instant" });
            if (typeof window.updateModalDots === 'function') window.updateModalDots();
        }, 10);
    };

    interactiveCards.forEach((card, index) => {
        card.addEventListener("click", (e) => {
            // Stop close button from propagating to card click
            if (e.target.closest(".card-modal__close-btn") || e.target.closest(".card-slider-close-btn")) return;
            
            const plusBtn = card.querySelector('.card__plus-btn');
            if (plusBtn) {
                plusBtn.classList.add('animate-pop-out');
                setTimeout(() => {
                    window.openMainOverlay(index);
                    plusBtn.classList.remove('animate-pop-out');
                }, 175);
            } else {
                window.openMainOverlay(index);
            }
        });
    });

    if (mobileModal) {
        // Mobile Modal Slider Logic & Close
        if (mobileModalClose) {
            mobileModalClose.addEventListener("click", () => {
                mobileModalClose.classList.add('animate-pop-out');
                setTimeout(() => {
                    mobileModal.classList.remove("is-active");
                    document.body.classList.remove("no-scroll");
                    mobileModalClose.classList.remove('animate-pop-out');
                }, 175);
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
                closeBtn.classList.add('animate-pop-out');
                setTimeout(() => {
                    mobileModal.classList.remove("is-active");
                    document.body.classList.remove("no-scroll");
                    closeBtn.classList.remove('animate-pop-out');
                }, 175);
            }
            
            // Delegate Next
            const nextBtn = e.target.closest(".card-slider-next-btn");
            if (nextBtn) {
                nextBtn.classList.add('animate-pop-out');
                setTimeout(() => nextBtn.classList.remove('animate-pop-out'), 175);
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let nextIndex = currentIndex + 1;
                if (nextIndex >= interactiveCards.length) nextIndex = 0;
                
                const slides = mobileModalScroll.querySelectorAll(".card-modal__slide");
                if (slides[nextIndex]) slides[nextIndex].scrollTop = 0;
                
                mobileModalScroll.scrollTo({ left: nextIndex * cardWidth, behavior: "smooth" });
            }
            
            // Delegate Prev
            const prevBtn = e.target.closest(".card-slider-prev-btn");
            if (prevBtn) {
                prevBtn.classList.add('animate-pop-out');
                setTimeout(() => prevBtn.classList.remove('animate-pop-out'), 175);
                const cardWidth = mobileModalScroll.clientWidth;
                let currentIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
                let prevIndex = currentIndex - 1;
                if (prevIndex < 0) prevIndex = interactiveCards.length - 1;
                
                const slides = mobileModalScroll.querySelectorAll(".card-modal__slide");
                if (slides[prevIndex]) slides[prevIndex].scrollTop = 0;
                
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
        
        // Reset scroll position of non-active slides when swiping finishes
        mobileModalScroll.addEventListener("scrollend", () => {
            const cardWidth = mobileModalScroll.clientWidth;
            let activeIndex = Math.round(mobileModalScroll.scrollLeft / cardWidth);
            
            const slides = mobileModalScroll.querySelectorAll(".card-modal__slide");
            slides.forEach((slide, idx) => {
                if (idx !== activeIndex) {
                    slide.scrollTop = 0;
                }
            });
        });
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
                    const parentList = item.closest('.faq-list');
                    const toggleBtn = item.querySelector('.faq-item__toggle');
                    
                    if (toggleBtn) {
                        toggleBtn.classList.add('animate-pop-out');
                        setTimeout(() => toggleBtn.classList.remove('animate-pop-out'), 175);
                    }
                    
                    if (parentList) {
                        // Lock the height on the very first interaction so it never jumps
                        if (!parentList.style.height) {
                            parentList.style.height = parentList.offsetHeight + 'px';
                        }
                        
                        // Toggle instantly, CSS handles the morphing animation
                        const allItems = parentList.querySelectorAll('.faq-item');
                        
                        if (!isActive) {
                            parentList.classList.add('has-active');
                            
                            // 1. Hide siblings first
                            allItems.forEach(i => {
                                if (i !== item) {
                                    i.classList.remove('is-active');
                                    i.classList.add('is-hidden');
                                }
                            });
                            
                            // 2. Wait for siblings to shrink, then expand active item
                            setTimeout(() => {
                                item.classList.add('is-active');
                                item.classList.remove('is-hidden');
                            }, 300); // Wait for sibling shrinking animation
                        } else {
                            // When closing, collapse the active item first
                            item.classList.remove('is-active');
                            
                            // Wait for it to collapse, then show siblings
                            setTimeout(() => {
                                allItems.forEach(i => {
                                    i.classList.remove('is-hidden');
                                });
                                parentList.classList.remove('has-active');
                            }, 300);
                        }
                    }
                });
            }
        });
        
        // Ensure height stays correct if window resizes while closed
        window.addEventListener('resize', () => {
            document.querySelectorAll('.faq-list').forEach(list => {
                if (!list.classList.contains('has-active')) {
                    list.style.height = 'auto'; // temporarily unlock
                    list.style.height = list.offsetHeight + 'px'; // relock
                }
            });
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

    const wordsA = ['Onrust', 'Creatieve<br>ideeën', 'Complexiteit', 'Ambitie', 'Techniek', 'Wensen'];
    const wordsB = ['Vertrouwen', 'Werkend<br>webdesign', 'Duidelijkheid', 'Resultaat', 'Gebruiksgemak', 'Oplossing'];
    
    let currentIndexA = 0;
    let currentIndexB = 0;

    function handleVennClick(clickedBubble) {
        if (!vennBubbleA || !vennBubbleB || !vennTextA || !vennTextB) return;

        // Apply scale-down to clicked bubble only
        clickedBubble.classList.add('scale-down');
        setTimeout(() => {
            clickedBubble.classList.remove('scale-down');
        }, 150);
        
        const isBubbleA = (clickedBubble === vennBubbleA);
        const contentToAnimate = isBubbleA ? vennBubbleA.querySelector('.venn-bubble-content') : vennBubbleB.querySelector('.venn-bubble-content');
        
        if (contentToAnimate) {
            contentToAnimate.style.opacity = '0';
            contentToAnimate.style.transform = 'translateY(10px)';
        }

        setTimeout(() => {
            // Update text independently
            if (isBubbleA) {
                currentIndexA = (currentIndexA + 1) % wordsA.length;
                vennTextA.innerHTML = wordsA[currentIndexA];
            } else {
                currentIndexB = (currentIndexB + 1) % wordsB.length;
                vennTextB.innerHTML = wordsB[currentIndexB];
            }
            
            // Fade back in
            if (contentToAnimate) {
                contentToAnimate.style.opacity = '1';
                contentToAnimate.style.transform = 'translateY(0)';
            }
        }, 200); // Wait 200ms for fade out
    }

    if (vennBubbleA) {
        vennBubbleA.addEventListener('click', () => handleVennClick(vennBubbleA));
    }

    if (vennBubbleB) {
        vennBubbleB.addEventListener('click', () => handleVennClick(vennBubbleB));
    }

    // =========================================
    // RADAR INTERACTIVE MOUSE TRACKING (DESKTOP)
    // =========================================
    const radarContainer = document.querySelector('.radar-container');
    const radarBeam = document.querySelector('.radar-beam-mask');
    const radarCounter = document.querySelector('.radar-words-counter');

    if (radarContainer && radarBeam && radarCounter) {
        let isHovering = false;
        let targetAngle = 0;
        let currentAngle = 0; // Starts at 0
        let lastTime = performance.now();

        radarContainer.addEventListener('mouseenter', () => {
            if (window.innerWidth <= 768) return;
            isHovering = true;
            radarBeam.classList.add('is-interactive');
            radarCounter.classList.add('is-interactive');
        });

        radarContainer.addEventListener('mousemove', (e) => {
            if (window.innerWidth <= 768) return;

            const rect = radarContainer.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            // Calculate angle from center to mouse
            let angleRad = Math.atan2(e.clientY - centerY, e.clientX - centerX);
            let angleDeg = angleRad * (180 / Math.PI);
            
            // Add 135 so that 0 rotation points right
            targetAngle = angleDeg + 135;
        });

        radarContainer.addEventListener('mouseleave', () => {
            if (window.innerWidth <= 768) return;
            isHovering = false;
            radarBeam.classList.remove('is-interactive');
            radarCounter.classList.remove('is-interactive');
            
            // Normalize current angle so it seamlessly continues spinning
            currentAngle = currentAngle % 360;
            if (currentAngle < 0) currentAngle += 360;
        });

        function animateRadar(time) {
            const dt = (time - lastTime) / 1000;
            lastTime = time;

            if (window.innerWidth > 768) {
                if (isHovering) {
                    // Smooth interpolate towards target angle
                    // Handle wrap-around for shortest path
                    let diff = targetAngle - currentAngle;
                    // Normalize diff to -180 to 180
                    diff = ((diff + 540) % 360) - 180;
                    
                    // Smoothness factor (higher = faster)
                    currentAngle += diff * 8 * dt;
                }
                
                radarBeam.style.transform = `rotate(${currentAngle}deg)`;
                radarCounter.style.transform = `rotate(${-currentAngle}deg)`;
            } else {
                // Clear inline styles on mobile so CSS animations take over
                radarBeam.style.transform = '';
                radarCounter.style.transform = '';
            }

            requestAnimationFrame(animateRadar);
        }
        
        requestAnimationFrame(animateRadar);
    }

    // =========================================
    // LOCALHOST DEBUG GRID (Alleen voor testomgevingen)
    // =========================================
    const isLocal = window.location.hostname === 'localhost' || 
                    window.location.hostname === '127.0.0.1' || 
                    window.location.protocol === 'file:';

    if (isLocal) {
        const debugStyles = document.createElement('style');
        debugStyles.innerHTML = `
            .debug-grid {
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                z-index: 9999; pointer-events: none;
                display: grid; grid-template-columns: repeat(12, 1fr);
                column-gap: var(--grid-gutter); padding: 0 var(--grid-margin);
                box-sizing: border-box; max-width: 100vw;
            }
            .debug-grid > div { background: rgba(255, 0, 0, 0.15); height: 100%; }
            .debug-grid::before {
                content: ''; position: absolute; left: 0; right: 0; top: 0;
                height: var(--nav-height); border-bottom: 2px dashed rgba(0, 0, 255, 0.5);
            }
            .debug-grid::after {
                content: ''; position: absolute; left: 0; right: 0; bottom: 0;
                height: var(--slide-bottom); border-top: 2px dashed rgba(0, 0, 255, 0.5);
            }
        `;
        document.head.appendChild(debugStyles);

        const debugGrid = document.createElement('div');
        debugGrid.className = 'debug-grid';
        debugGrid.id = 'debug-grid';
        for (let i = 0; i < 12; i++) {
            debugGrid.appendChild(document.createElement('div'));
        }
        document.body.appendChild(debugGrid);
        
        // Toggle met de G-toets
        document.addEventListener('keydown', (e) => {
            if (e.key.toLowerCase() === 'g' && !e.target.matches('input, textarea')) {
                debugGrid.style.display = debugGrid.style.display === 'none' ? 'grid' : 'none';
            }
        });
        
        console.log("🛠️ Localhost/Test omgeving gedetecteerd: Debug Grid ingeschakeld. Druk op 'G' om te verbergen/tonen.");
    }

});

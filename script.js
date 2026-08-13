/* ============================================
   GRUT — Slide Architecture Navigation
   ============================================ */

function setVw() {
    document.documentElement.style.setProperty('--vw', `${document.documentElement.clientWidth}px`);
}
setVw();
window.addEventListener('resize', setVw);

document.addEventListener('DOMContentLoaded', () => {

    const slidesContainer = document.getElementById('slidesContainer');
    const nav = document.getElementById('nav');
    const navLogo = document.getElementById('navLogo');
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



    // ---- Footer Scroll-to-top button ----
    const footerScrollBtn = document.querySelector('.footer-scroll-top');
    if (footerScrollBtn && slidesContainer) {
        footerScrollBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nav.classList.remove('nav--contact-open');
            slidesContainer.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---- Logo Scroll Animation (wordmark → beeldmerk) ----
    if (slidesContainer && nav) {
        let ticking = false;
        slidesContainer.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (slidesContainer.scrollTop > 80) {
                        if (!nav.classList.contains('nav--scrolled')) {
                            nav.classList.add('nav--scrolled');
                        }
                    } else {
                        if (nav.classList.contains('nav--scrolled')) {
                            nav.classList.remove('nav--scrolled');
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
            const isWhiteInit = navMobileLabelCenter.textContent.includes('Aangenaam') || navMobileLabelCenter.textContent.includes('Menu');
            navMobileLabelCenter.style.color = isWhiteInit ? 'var(--color-cream)' : 'var(--color-yellow)';
        }

        if (navMobileLabelCenter && navMobileLabelTop && navMobileLabelBottom && navMobileLabelSlider) {
            if (navMobileLabelCenter.textContent !== newLabelText && window.navLabelTimeoutText !== newLabelText) {
                clearTimeout(window.navLabelTimeout);
                window.navLabelTimeoutText = newLabelText;
                const isWhite = newLabelText.includes('Aangenaam') || newLabelText.includes('Menu');
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
                
                // Show arrow on logo if we are on the contact (last) slide
                if (navLogo) {
                    if (id === 'contact') {
                        navLogo.classList.add('show-arrow');
                    } else {
                        navLogo.classList.remove('show-arrow');
                    }
                }
                
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
                const newLabelText = currentSlideLabel === 'Home' ? 'Aangenaam' : currentSlideLabel;
                
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
    const navContactStatesWrapper = document.getElementById('navContactStatesWrapper');
    const navContactTriggerPhone = document.getElementById('navContactTriggerPhone');

    function animateNavWidth(callback) {
        const navPill = document.querySelector('.nav__pill');
        if (!navPill) {
            callback();
            return;
        }
        
        // Lock start width
        const startWidth = navPill.offsetWidth;
        navPill.style.width = startWidth + 'px';
        navPill.style.transition = 'none';
        
        callback();
        
        // Measure target width
        navPill.style.width = 'max-content';
        const targetWidth = navPill.offsetWidth;
        
        // Revert and reflow
        navPill.style.width = startWidth + 'px';
        navPill.offsetHeight; // Force reflow
        
        // Animate
        navPill.style.transition = 'width 0.4s var(--ease-spring), transform 0.3s ease';
        navPill.style.width = targetWidth + 'px';
        
        // Cleanup
        if (window.navPillCleanupTimeout) {
            clearTimeout(window.navPillCleanupTimeout);
        }
        window.navPillCleanupTimeout = setTimeout(() => {
            navPill.style.width = '';
            navPill.style.transition = '';
        }, 400);
    }

    if (navCtaBtn) {
        navCtaBtn.addEventListener('click', (e) => {
            e.preventDefault();
            animateNavWidth(() => {
                nav.classList.add('nav--contact-open');
            });
        });
    }

    if (navContactClose) {
        navContactClose.addEventListener('click', (e) => {
            e.preventDefault();
            animateNavWidth(() => {
                nav.classList.remove('nav--contact-open');
            });
            // Reset to default silently after close animation finishes
            setTimeout(() => {
                if (navContactStatesWrapper) {
                    navContactStatesWrapper.classList.remove('is-phone');
                }
            }, 400);
        });
    }

    if (navContactTriggerPhone) {
        navContactTriggerPhone.addEventListener('click', (e) => {
            e.preventDefault();
            if (navContactStatesWrapper) {
                animateNavWidth(() => {
                    navContactStatesWrapper.classList.add('is-phone');
                });
            }
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
        // Icon swap behavior (Desktop specific)
        const iconCopy = btn.querySelector('.icon-copy');
        const iconCheck = btn.querySelector('.icon-check');
        
        if (iconCopy && iconCheck) {
            iconCopy.style.display = 'none';
            iconCheck.style.display = 'block';
            setTimeout(() => {
                iconCheck.style.display = 'none';
                iconCopy.style.display = 'block';
            }, 1600);
        }

        // Standard text swap behavior (Mobile)
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
        ['navCopyEmailBtn', 'mobCopyEmail', 'letsgo@grutdesigners.nl']
    ];

    copyTargets.forEach(([navId, mobId, text]) => {
        [document.getElementById(navId), document.getElementById(mobId)].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => animateCopyText(btn, 'Adres gekopieerd'));
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
                            animateCopyText(btn, 'Adres gekopieerd');
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
                updateMobileLabel(currentSlideLabel === 'Home' ? 'Aangenaam' : currentSlideLabel, false, 0);
            }
        });
    }

    if (mobMenuClose) {
        mobMenuClose.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
            updateMobileLabel(currentSlideLabel === 'Home' ? 'Aangenaam' : currentSlideLabel, false, 0);
        });
    }

    // Team Bios Overlay Logic
    const interactiveCards = Array.from(document.querySelectorAll(".card")).filter(card => Array.from(card.children).some(c => c.classList.contains("card-overlay-content") || c.classList.contains("card__modal-template")));
    const mobileModal = document.getElementById("mobile-bio-modal");
    const mobileModalScroll = mobileModal ? mobileModal.querySelector(".card-modal__scroll") : null;
    const mobileModalDots = mobileModal ? mobileModal.querySelectorAll(".card-slider__dot") : [];
    const mobileModalClose = mobileModal ? mobileModal.querySelector(".card-modal__close") : null;
    
    let currentModalScope = null;
    let currentModalCardsLength = 0;

    // Helper: Build Card Modal
    function buildCardModal(cards, scope) {
        if (currentModalScope === scope || !mobileModalScroll) return;
        currentModalScope = scope;
        currentModalCardsLength = cards.length;
        
        mobileModalScroll.innerHTML = ""; // Clear
        
        cards.forEach((card, i) => {
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
            
            // Add role to tags if it exists (e.g., "Digital designer")
            if (role && !tagsArray.includes(role)) {
                tagsArray.push(role);
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
                            <button class="overlay-footer-btn overlay-footer-btn--secondary card-slider-next-btn">Volgende</button>
                            <button class="overlay-header__close card-slider-close-btn" aria-label="Sluiten">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            mobileModalScroll.insertAdjacentHTML("beforeend", slideHtml);
        });
        
        // Dynamically generate dots
        const dotsContainer = mobileModal.querySelector(".card-slider__dots");
        if (dotsContainer) {
            dotsContainer.innerHTML = cards.map((_, idx) => 
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
        
    }

    window.openMainOverlay = function(cards, index, scope) {
        buildCardModal(cards, scope);
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
            
            const scope = card.closest('.slide') || document;
            const scopeCards = Array.from(scope.querySelectorAll(".card")).filter(card => Array.from(card.children).some(c => c.classList.contains("card-overlay-content") || c.classList.contains("card__modal-template")));
            const localIndex = scopeCards.indexOf(card);
            
            const plusBtn = card.querySelector('.card__plus-btn');
            if (plusBtn) {
                plusBtn.classList.add('animate-pop-out');
                setTimeout(() => {
                    window.openMainOverlay(scopeCards, localIndex, scope);
                    plusBtn.classList.remove('animate-pop-out');
                }, 175);
            } else {
                window.openMainOverlay(scopeCards, localIndex, scope);
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
                if (nextIndex >= currentModalCardsLength) nextIndex = 0;
                
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
                if (prevIndex < 0) prevIndex = currentModalCardsLength - 1;
                
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
            if (targetIndex > currentModalCardsLength - 1) targetIndex = currentModalCardsLength - 1;
            
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
        let hasDragged = false;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            hasDragged = false;
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
            
            if (Math.abs(walk) > 5) {
                hasDragged = true;
            }
            
            slider.scrollLeft = scrollLeft - walk;
        });

        // Prevent click if we dragged
        slider.addEventListener('click', (e) => {
            if (hasDragged) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true); // Use capture phase to intercept before children
    });

    // =========================================
    // VENN DIAGRAM ANIMATION
    // =========================================
    const vennBubbleA = document.getElementById('venn-bubble-a');
    const vennBubbleB = document.getElementById('venn-bubble-b');
    const vennTextA = document.getElementById('venn-text-a');
    const vennTextB = document.getElementById('venn-text-b');
    const vennLogo = document.querySelector('.venn-logo-container');

    const wordsA = ['Digitale<br>ambities', 'Slim<br>werken', 'Efficiëntie', 'Veilige<br>website', 'Sneller<br>vooruit', 'Handwerk<br>wegnemen', 'Zichtbaarheid', 'Upselling', 'Meer<br>conversie', 'Kwaliteit<br>van lead', 'Meer<br>omzet', 'Meer<br>offertes', 'Hogere ROI', 'WCAG<br>proof', 'Conversies'];
    const wordsB = ['Optimale<br>beleving', 'Ontzorgt<br>worden', 'Consistente<br>ervaring', 'Snelle<br>interacties', 'Intuïtieve<br>navigatie', 'Snelle<br>interactie', 'Weinig<br>laadtijd', 'Visuals &<br>videos', 'Slimme<br>check-out', 'Privacy', 'Overzicht', 'Snelheid', 'Snel<br>scannen', 'Informatie'];
    
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

});
        // Custom JS path morpher voor Safari iOS (omzeilt CSS transition bugs)
        function morphPath(pathEl, startD, endD, durationMs) {
            const startNums = startD.match(/-?\d+(\.\d+)?/g).map(Number);
            const endNums = endD.match(/-?\d+(\.\d+)?/g).map(Number);
            const textParts = startD.split(/-?\d+(?:\.\d+)?/g);
            
            let startTime = null;
            
            // Custom ease-out-back curve voor de perfecte bounce
            function easeOutBack(x) {
                const c1 = 1.3;
                const c3 = c1 + 1;
                return 1 + c3 * Math.pow(x - 1, 3) + c1 * Math.pow(x - 1, 2);
            }
            
            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                let progress = (timestamp - startTime) / durationMs;
                if (progress > 1) progress = 1;
                
                const eased = easeOutBack(progress);
                
                let newD = textParts[0];
                for (let i = 0; i < startNums.length; i++) {
                    const currentVal = startNums[i] + (endNums[i] - startNums[i]) * eased;
                    // Format om afrondingsfouten in Safari te voorkomen
                    newD += currentVal.toFixed(2) + textParts[i+1];
                }
                
                pathEl.setAttribute('d', newD);
                
                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            }
            requestAnimationFrame(step);
        }

        // Tab switching logic for slide 3 (missie-copy) with sliding indicator
        const switcher = document.querySelector('#missie-copy .missie__tabs-switcher');
        if (switcher) {
            const tabButtons = switcher.querySelectorAll('.tab-switch-btn');
            const tabContents = document.querySelectorAll('#missie-copy .missie__tab-content');
            const bgIndicator = document.getElementById('tabBgIndicator');
            
            
            function updateIndicator() {
                const activeBtn = switcher.querySelector('.tab-switch-btn.active');
                if (activeBtn) {
                    const width = activeBtn.offsetWidth;
                    const left = activeBtn.offsetLeft;
                    requestAnimationFrame(() => {
                        bgIndicator.style.setProperty('--indicator-width', `${width}px`);
                        bgIndicator.style.setProperty('--indicator-x', `${left}px`);
                    });
                }
            }


            function splitTextIntoLinesAndAnimate(element) {
                if (!element) return;
                if (!element.dataset.originalHtml) {
                    element.dataset.originalHtml = element.innerHTML;
                }
                element.innerHTML = element.dataset.originalHtml;
                
                const lines = element.querySelectorAll('.missie-line');
                lines.forEach(line => {
                    const originalContent = line.innerHTML;
                    line.innerHTML = '';
                    
                    const outer = document.createElement('span');
                    outer.style.display = 'inline-block';
                    outer.style.overflow = 'hidden';
                    outer.style.verticalAlign = 'bottom';
                    
                    outer.style.paddingTop = '0.1em';
                    outer.style.paddingBottom = '0.1em';
                    outer.style.marginTop = '-0.1em';
                    outer.style.marginBottom = '-0.1em';
                    
                    const inner = document.createElement('span');
                    inner.className = 'missie-line-inner';
                    inner.style.display = 'inline-block';
                    inner.style.animation = `lamelRevealDown 0.5s ease-in-out both`;
                    
                    inner.innerHTML = originalContent;
                    
                    outer.appendChild(inner);
                    line.appendChild(outer);
                });
            }
            
            // Initial positioning and updates
            setTimeout(updateIndicator, 150);
            window.addEventListener('resize', updateIndicator);
            if (document.fonts) {
                document.fonts.ready.then(updateIndicator);
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.classList.contains('active')) return;
                    
                    const targetTabId = btn.getAttribute('data-tab');
                    
                    // Update active button and prevent rapid clicks during transition
                    tabButtons.forEach(b => {
                        b.classList.remove('active');
                        b.style.pointerEvents = 'none'; 
                    });
                    btn.classList.add('active');
                    
                    updateIndicator();
                    
                    // Find currently active content
                    const oldContent = Array.from(tabContents).find(c => c.classList.contains('active'));
                    
                    if (oldContent && oldContent.id !== targetTabId) {
                        const oldH2s = oldContent.querySelectorAll('.missie__text');
                        oldH2s.forEach(oldH2 => {
                            // Find all animated inner spans and slide them down
                            const inners = oldH2.querySelectorAll('.missie-line-inner');
                            inners.forEach(inner => {
                                inner.style.animation = `lamelHideDown 0.3s ease-in-out both`;
                            });
                        });
                        
                        // Wait for exit animation to finish
                        setTimeout(() => {
                            tabContents.forEach(c => c.classList.remove('active'));
                            
                            const newContent = document.getElementById(targetTabId);
                            if (newContent) {
                                newContent.classList.add('active');
                                const newH2s = newContent.querySelectorAll('.missie__text');
                                newH2s.forEach(newH2 => {
                                    splitTextIntoLinesAndAnimate(newH2);
                                });
                                tabButtons.forEach(b => b.style.pointerEvents = 'auto');
                            }
                        }, 300);
                    } else if (!oldContent) {
                        // Fallback if no old content
                        const newContent = document.getElementById(targetTabId);
                        if (newContent) {
                            newContent.classList.add('active');
                            const newH2s = newContent.querySelectorAll('.missie__text');
                            newH2s.forEach(newH2 => {
                                splitTextIntoLinesAndAnimate(newH2);
                            });
                            tabButtons.forEach(b => b.style.pointerEvents = 'auto');
                        }
                    }
                    
                });
            });

            // Initial trigger for the first active tab
            const initialActive = switcher.querySelector('.tab-switch-btn.active');
            if (initialActive) {
                const targetTabId = initialActive.getAttribute('data-tab');
                const activeContent = document.getElementById(targetTabId);
                if (activeContent) {
                    const h2s = activeContent.querySelectorAll('.missie__text');
                    h2s.forEach(h2 => {
                        setTimeout(() => splitTextIntoLinesAndAnimate(h2), 100);
                    });
                }
            }
        }

        // Tab switching logic for FAQ slide
        const faqSwitcher = document.querySelector('.faq__tabs-switcher');
        if (faqSwitcher) {
            const faqTabButtons = faqSwitcher.querySelectorAll('.tab-switch-btn');
            const faqTabContents = document.querySelectorAll('.faq__tab-content');
            const faqBgIndicator = document.getElementById('faqBgIndicator');
            
            function updateFaqIndicator() {
                const activeBtn = faqSwitcher.querySelector('.tab-switch-btn.active');
                if (activeBtn) {
                    const width = activeBtn.offsetWidth;
                    const left = activeBtn.offsetLeft;
                    requestAnimationFrame(() => {
                        faqBgIndicator.style.setProperty('--indicator-width', `${width}px`);
                        faqBgIndicator.style.setProperty('--indicator-x', `${left}px`);
                    });
                }
            }
            
            setTimeout(updateFaqIndicator, 150);
            window.addEventListener('resize', updateFaqIndicator);
            if (document.fonts) {
                document.fonts.ready.then(updateFaqIndicator);
            }

            faqTabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetTabId = btn.getAttribute('data-tab');
                    
                    // Update active button
                    faqTabButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    updateFaqIndicator();
                    
                    // Find the currently active tab
                    const oldTab = Array.from(faqTabContents).find(c => c.classList.contains('active'));
                    
                    if (oldTab && oldTab.id !== targetTabId) {
                        oldTab.classList.remove('active');
                        oldTab.classList.add('exiting');
                        
                        setTimeout(() => {
                            oldTab.classList.remove('exiting');
                        }, 300); // Wait for textSlideOut animation
                    }
                    
                    const newTab = document.getElementById(targetTabId);
                    if (newTab && (!oldTab || oldTab.id !== targetTabId)) {
                        // If there is an old tab, wait for it to exit before showing new
                        const delay = oldTab ? 300 : 0;
                        setTimeout(() => {
                            newTab.classList.add('active');
                        }, delay);
                    }
                });
            });
        }



        // Setup animations for each mission slide / grut-anim-scene
        document.querySelectorAll('.grut-anim-scene').forEach((grutAnimScene) => {
            const introWrapper = grutAnimScene.querySelector('.intro-wrapper');
            const introNative = grutAnimScene.querySelector('.intro-native');
            const buttonsContainer = grutAnimScene.querySelector('.buttons-container');
            const introPath = grutAnimScene.querySelector('path');
            const words = grutAnimScene.querySelectorAll('.carousel-word');
            const btnLeft = grutAnimScene.querySelector('.left-btn');
            const btnCenter = grutAnimScene.querySelector('.center-btn');
            const btnRight = grutAnimScene.querySelector('.right-btn');
            
            // Carousel setup
            let currentWordIdx = 0;
            let isAnimatingCarousel = false;
            let carouselInterval = null; 
            let pendingReset = false; 

            function slideToWord(targetIdx) {
                if (currentWordIdx === targetIdx) return;
                isAnimatingCarousel = true;

                const oldWord = words[currentWordIdx];
                oldWord.classList.remove('is-center');
                oldWord.classList.add('is-right');

                currentWordIdx = targetIdx;
                const newWord = words[currentWordIdx];
                newWord.classList.add('is-center');

                setTimeout(() => {
                    oldWord.classList.add('no-transition');
                    oldWord.classList.remove('is-right');
                    oldWord.getBoundingClientRect();
                    oldWord.classList.remove('no-transition');
                    isAnimatingCarousel = false;

                    if (pendingReset && currentWordIdx !== 0 && !carouselInterval) {
                        pendingReset = false;
                        slideToWord(0);
                    }
                }, 400); 
            }

            function nextWord() {
                if (isAnimatingCarousel) return; 
                slideToWord((currentWordIdx + 1) % words.length);
            }

            introNative.addEventListener('mouseenter', () => {
                if (introNative.classList.contains('is-interactive')) {
                    pendingReset = false; 
                    if (!carouselInterval) {
                        nextWord(); 
                        carouselInterval = setInterval(nextWord, 2000); 
                    }
                }
            });

            introNative.addEventListener('mouseleave', () => {
                clearInterval(carouselInterval);
                carouselInterval = null;
                
                if (currentWordIdx !== 0) {
                    if (isAnimatingCarousel) {
                        pendingReset = true; 
                    } else {
                        slideToWord(0); 
                    }
                }
            });

            // Toegankelijkheid: Keyboard navigatie
            introNative.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (introNative.classList.contains('is-interactive')) {
                        e.preventDefault();
                        startFase2();
                    }
                }
            });

            // Timeout tracker voor reset functionaliteit
            let animTimeouts = [];
            let isAnimationPlaying = false; // Voorkom overlappende triggers door scroll bounce
            
            function clearAnimTimeouts() {
                animTimeouts.forEach(clearTimeout);
                animTimeouts = [];
            }

            function resetAnimation() {
                clearAnimTimeouts();
                clearInterval(carouselInterval);
                carouselInterval = null;
                currentWordIdx = 0;
                isAnimatingCarousel = false;
                pendingReset = false;
                
                btnLeft.classList.remove('is-pulsing');
                btnCenter.classList.remove('is-pulsing');
                btnRight.classList.remove('is-pulsing');

                grutAnimScene.className = 'grut-anim-scene';
                introNative.className = 'intro-native';
                introNative.removeAttribute('style');
                introNative.style.pointerEvents = 'none';
                introNative.removeEventListener('click', startFase2);
                
                introPath.setAttribute('d', "M113.531 5.78533L113.531 5.78546C106.676 12.6729 97.0775 17.0809 86.3825 17.0809C73.2195 17.0809 61.7018 10.1934 54.8461 0H0C6.58153 26.1723 24.4065 47.9365 48.2645 59.5074C59.7822 65.0173 72.9453 68.0478 86.3825 68.0478C100.094 68.0478 112.983 65.0173 124.501 59.5074C148.359 47.9365 166.458 26.1723 173.039 0H118.193C116.822 1.92844 115.177 3.85689 113.531 5.78533Z");
                
                words.forEach((w) => {
                    w.className = 'carousel-word';
                    w.style.opacity = '';
                });
                isAnimationPlaying = false;
            }

            function playAnimation() {
                if (isAnimationPlaying) return; // Voorkom dat scroll bounce de animatie afbreekt
                isAnimationPlaying = true;
                
                resetAnimation();
                isAnimationPlaying = true; // Zet m weer op true na de reset
                
                // Verberg direct the first word zodat het netjes inschuift
                words.forEach(w => w.classList.remove('is-center'));

                // Fase 1: De grote choreografie start na 2 seconden als het in beeld komt
                animTimeouts.push(setTimeout(() => {
                    const startD = "M113.531 5.78533L113.531 5.78546C106.676 12.6729 97.0775 17.0809 86.3825 17.0809C73.2195 17.0809 61.7018 10.1934 54.8461 0H0C6.58153 26.1723 24.4065 47.9365 48.2645 59.5074C59.7822 65.0173 72.9453 68.0478 86.3825 68.0478C100.094 68.0478 112.983 65.0173 124.501 59.5074C148.359 47.9365 166.458 26.1723 173.039 0H118.193C116.822 1.92844 115.177 3.85689 113.531 5.78533Z";
                    
                    const isMobile = window.innerWidth <= 768;
                    const targetHeightPx = isMobile ? 36 : 48;
                    const extraPadding = isMobile ? 28 : 48;
                    const textWidth = words[0].offsetWidth + extraPadding;
                    
                    const pxToSvg = 69 / 72;
                    const svgWidth = textWidth * pxToSvg;
                    const svgHeight = targetHeightPx * pxToSvg;
                    const r = svgHeight / 2;
                    const k = r * 0.55228;
                    
                    const cx = 174 / 2;
                    const cy = 69 / 2;
                    
                    const left = cx - (svgWidth / 2);
                    const right = cx + (svgWidth / 2);
                    const top = cy - r;
                    const bottom = cy + r;
                    
                    const f = n => n.toFixed(3);
                    
                    const endD = `M${f(right-r)} ${f(top)}L${f(right-r)} ${f(top)}C${f(right-r)} ${f(top)} ${f(cx)} ${f(top)} ${f(cx)} ${f(top)}C${f(cx)} ${f(top)} ${f(left+r)} ${f(top)} ${f(left+r)} ${f(top)}H${f(left+r)}C${f(left+r-k)} ${f(top)} ${f(left)} ${f(cy-k)} ${f(left)} ${f(cy)}C${f(left)} ${f(cy+k)} ${f(left+r-k)} ${f(bottom)} ${f(left+r)} ${f(bottom)}C${f(left+r)} ${f(bottom)} ${f(right-r)} ${f(bottom)} ${f(right-r)} ${f(bottom)}C${f(right-r+k)} ${f(bottom)} ${f(right)} ${f(cy+k)} ${f(right)} ${f(cy)}H${f(right)}C${f(right)} ${f(cy-k)} ${f(right-r+k)} ${f(top)} ${f(right-r)} ${f(top)}Z`;

                    // Zet de HTML button direct op de juiste breedte
                    introNative.style.transition = 'none';
                    introNative.style.width = textWidth + 'px';
                    introNative.style.height = targetHeightPx + 'px';
                    introNative.getBoundingClientRect(); // force reflow
                    introNative.style.transition = '';

                    morphPath(introPath, startD, endD, 700);
                    
                    animTimeouts.push(setTimeout(() => {
                        grutAnimScene.classList.add('is-native'); 
                        introNative.getBoundingClientRect(); 

                        words[0].classList.add('is-center');
                        
                        animTimeouts.push(setTimeout(() => {
                            introNative.classList.add('is-interactive'); 
                            introNative.style.pointerEvents = 'all'; 
                            introNative.style.cursor = 'pointer';
                            introNative.addEventListener('click', startFase2);

                            // UX Fix: Start op mobiel de woorden carrousel
                            if (isMobile) {
                                animTimeouts.push(setTimeout(() => {
                                    if (!carouselInterval) {
                                        carouselInterval = setInterval(nextWord, 2000);
                                    }
                                }, 1500));
                            }
                        }, 800));

                    }, 800));

                }, 2000));
            }

            function startFase2() {
                clearInterval(carouselInterval);
                carouselInterval = null;
                introNative.classList.remove('is-interactive'); 
                introNative.removeEventListener('click', startFase2);
                
                words.forEach(w => w.style.opacity = '0'); 
                
                animTimeouts.push(setTimeout(() => {
                    introNative.style.height = buttonsContainer.offsetHeight + 'px';
                    introNative.style.width = buttonsContainer.offsetWidth + 'px';
                    introNative.style.borderRadius = '24px';

                    animTimeouts.push(setTimeout(() => {
                        grutAnimScene.classList.add('is-cut-ready'); 
                        grutAnimScene.classList.add('hide-native'); 

                        buttonsContainer.getBoundingClientRect();

                        requestAnimationFrame(() => {
                            grutAnimScene.classList.add('is-cut');
                        });

                        animTimeouts.push(setTimeout(() => {
                            grutAnimScene.classList.add('is-text-visible');
                        }, 600));

                    }, 800));

                }, 300));
            }

            // Intersection Observer voor autoplay on scroll
            const observerOptions = {
                root: null,
                rootMargin: '100px 0px',
                threshold: 0.15
            };

            const animObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        playAnimation();
                    } else {
                        resetAnimation();
                    }
                });
            }, observerOptions);

            animObserver.observe(grutAnimScene);

            if (btnLeft) btnLeft.addEventListener('click', () => window.openMainOverlay([], 0, grutAnimScene));
            if (btnCenter) btnCenter.addEventListener('click', () => window.openMainOverlay([], 1, grutAnimScene));
            if (btnRight) btnRight.addEventListener('click', () => window.openMainOverlay([], 2, grutAnimScene));

        });
    // Custom cursor logic for Venn Diagram (Bubbels)
    (function initCustomCursor() {
        const customCursor = document.getElementById('custom-cursor');
        const vennBubbles = document.querySelectorAll('.venn-bubble, [data-cursor-text]');
        
        if (customCursor && vennBubbles.length > 0) {
            const cursorTextEl = customCursor.querySelector('span:first-child');
            const cursorEmojiEl = customCursor.querySelector('.custom-cursor-emoji');
            
            let mouseX = 0;
            let mouseY = 0;
            let cursorX = mouseX;
            let cursorY = mouseY;
            let isHovering = false;

            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                if (!isHovering) {
                    cursorX = mouseX;
                    cursorY = mouseY;
                    customCursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0)`;
                }
            });

            vennBubbles.forEach(bubble => {
                bubble.addEventListener('mouseenter', () => {
                    isHovering = true;
                    if (bubble.hasAttribute('data-cursor-text')) {
                        cursorTextEl.textContent = bubble.getAttribute('data-cursor-text');
                        if (bubble.hasAttribute('data-cursor-emoji')) {
                            cursorEmojiEl.textContent = bubble.getAttribute('data-cursor-emoji');
                            cursorEmojiEl.style.display = 'inline';
                        } else {
                            cursorEmojiEl.style.display = 'none';
                        }
                    } else {
                        cursorTextEl.textContent = 'Push me';
                        cursorEmojiEl.textContent = '👇';
                        cursorEmojiEl.style.display = 'inline';
                    }
                    customCursor.classList.add('active');
                });

                bubble.addEventListener('mouseleave', () => {
                    isHovering = false;
                    customCursor.classList.remove('active');
                });
            });

            function updateCursor() {
                if (isHovering) {
                    cursorX += (mouseX - cursorX) * 0.15; 
                    cursorY += (mouseY - cursorY) * 0.15;
                    customCursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0)`;
                }
                requestAnimationFrame(updateCursor);
            }
            updateCursor();
        }
    })();

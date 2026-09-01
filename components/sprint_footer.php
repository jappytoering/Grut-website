<script>
    function handlePrototypeInterest(btn, event) {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth < 768;

        if (!isMobile) {
            // Desktop: Prevent mailto and copy ONLY email
            if (event) event.preventDefault();
            
            navigator.clipboard.writeText("letsgo@grutdesigners.nl").then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = `
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span style="display: inline-block; vertical-align: middle;">Emailadres gekopieerd</span>
                `;
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 3000);
            });
        } else {
            // Mobile: let mailto open normally, but show "Done" feedback
            // Wait a tiny bit so the mailto fires before we replace the DOM elements
            setTimeout(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = `
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span style="display: inline-block; vertical-align: middle;">Done</span>
                `;
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 3000);
            }, 100);
        }
    }
        // Photo Slider Drag Logic
        const slider = document.getElementById('photo-slider');
        let isDown = false;
        let startX;
        let scrollLeft;

        if (slider) {
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
                const walk = (x - startX) * 2; // Scroll-fast
                slider.scrollLeft = scrollLeft - walk;
            });
        }
        // Hero Auto Slider Logic
        const heroSlider = document.getElementById('heroAutoSlider');
        if (heroSlider) {
            const slides = document.querySelectorAll('.hero-slider__slide');
            const nextImgEl = document.getElementById('heroSliderNextImg');
            const progressRing = document.getElementById('heroSliderProgress');
            const previewBtn = document.getElementById('heroSliderPreviewBtn');
            
            let currentSlideIdx = 0;
            const slideDuration = 5000; // 5 seconds per slide
            let startTime = null;
            let animationFrameId = null;
            
            // Circumference of r=30 circle
            const circumference = 2 * Math.PI * 30;
            progressRing.style.strokeDasharray = circumference;
            progressRing.style.strokeDashoffset = circumference;
            
            function updateSlides(newIndex) {
                // Hide current
                slides[currentSlideIdx].classList.remove('is-active');
                
                // Update index
                currentSlideIdx = newIndex;
                if (currentSlideIdx >= slides.length) currentSlideIdx = 0;
                
                // Show new
                slides[currentSlideIdx].classList.add('is-active');
                
                // Update preview image to the one AFTER current
                const nextIdx = (currentSlideIdx + 1) % slides.length;
                const nextImgSrc = slides[nextIdx].querySelector('img').src;
                nextImgEl.src = nextImgSrc;
            }
            
            function animateProgress(timestamp) {
                if (!startTime) startTime = timestamp;
                const elapsed = timestamp - startTime;
                
                const progress = Math.min(elapsed / slideDuration, 1);
                // offset goes from circumference to 0
                const offset = circumference - (progress * circumference);
                progressRing.style.strokeDashoffset = offset;
                
                if (progress < 1) {
                    animationFrameId = requestAnimationFrame(animateProgress);
                } else {
                    // Time's up, next slide!
                    updateSlides(currentSlideIdx + 1);
                    startTime = null;
                    animationFrameId = requestAnimationFrame(animateProgress);
                }
            }
            
            // Initialize
            updateSlides(0);
            animationFrameId = requestAnimationFrame(animateProgress);
            
            // Click to skip
            previewBtn.addEventListener('click', () => {
                cancelAnimationFrame(animationFrameId);
                updateSlides(currentSlideIdx + 1);
                startTime = null;
                // Instantly update visual state of ring to empty
                progressRing.style.strokeDashoffset = circumference;
                animationFrameId = requestAnimationFrame(animateProgress);
            });
        }
    </script>
    <!-- Form Validatie Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('.prototype-cta-block form');
        if (!form) return;
        
        const pageLoadTime = new Date().toISOString();

        // SVG Icon template
        const checkIconSvg = `<svg class="valid-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;

        // Regels voor velden
        const validationRules = {
            'first_name': {
                validate: val => val.trim().length >= 2,
                message: 'Minimaal 2 tekens vereist.'
            },
            'last_name': {
                validate: val => val.trim().length >= 2,
                message: 'Minimaal 2 tekens vereist.'
            },
            'email': {
                validate: val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
                message: 'Voer een geldig e-mailadres in.'
            },
            'phone': {
                validate: val => {
                    if (val.trim() === '') return true; // Optional, valid if empty
                    return val.replace(/\D/g, '').length >= 8;
                },
                message: 'Voer een geldig telefoonnummer in (min. 8 cijfers).'
            },
            'case_description': {
                validate: val => {
                    const len = val.trim().length;
                    return len >= 5 && len <= 200;
                },
                message: 'Beschrijf je casus in minimaal 5 tekens.'
            }
        };

        const validateField = (input, eventType) => {
            const rule = validationRules[input.name];
            if (!rule) return true;
            
            const formGroup = input.closest('.form-group');
            if (!formGroup) return true;
            
            const errorContainer = formGroup.querySelector('.error-message');
            const isValid = rule.validate(input.value);
            
            // Checkmark icoon toevoegen indien afwezig
            if (!formGroup.querySelector('.valid-icon') && input.type !== 'checkbox' && input.type !== 'radio') {
                input.insertAdjacentHTML('afterend', checkIconSvg);
            }

            if (isValid) {
                // Telefoon is leeg? Verwijder dan is-valid (neutraal houden)
                if (input.name === 'phone' && input.value.trim() === '') {
                    formGroup.classList.remove('is-valid');
                } else {
                    formGroup.classList.add('is-valid');
                }
                formGroup.classList.remove('is-invalid');
                if (errorContainer) errorContainer.textContent = '';
                return true;
            } else {
                formGroup.classList.remove('is-valid');
                
                if (eventType === 'blur' || eventType === 'submit') {
                    if (input.value.trim() === '' && !input.required) {
                        formGroup.classList.remove('is-invalid');
                        if (errorContainer) errorContainer.textContent = '';
                        return true;
                    }
                    
                    formGroup.classList.add('is-invalid');
                    if (errorContainer) errorContainer.textContent = rule.message;
                }
                return false;
            }
        };

        // Attach listeners
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') return;
            
            input.addEventListener('input', () => validateField(input, 'input'));
            input.addEventListener('blur', () => validateField(input, 'blur'));
        });

        // Submit listener
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let formIsValid = true;
            inputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') return;
                const fieldIsValid = validateField(input, 'submit');
                if (!fieldIsValid) formIsValid = false;
            });
            
            if (!formIsValid) {
                const mainError = form.querySelector('.form-error-message');
                if (mainError) {
                    mainError.textContent = 'Controleer de gemarkeerde velden hierboven en probeer het opnieuw.';
                    mainError.style.display = 'block';
                }
            } else {
                const mainError = form.querySelector('.form-error-message');
                if (mainError) mainError.style.display = 'none';
                
                // Show loading state
                const submitBtn = form.querySelector('.cta-submit-btn');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnSpinner = submitBtn.querySelector('.btn-spinner');
                
                if (submitBtn) submitBtn.disabled = true;
                if (btnText) btnText.style.opacity = '0.5';
                if (btnSpinner) btnSpinner.style.display = 'inline-block';
                
                // Perform actual submission
                const formData = new FormData(form);
                formData.append('source_url', window.location.href);
                formData.append('browser_timestamp', pageLoadTime);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const header = document.querySelector('.cta-header');
                        const fields = form.querySelector('.cta-form-fields');
                        const bottomFields = form.querySelector('.cta-form-bottom-fields');
                        const actions = form.querySelector('.cta-form-actions');
                        const successMessage = form.querySelector('.cta-success-message');
                        
                        if (header) header.style.display = 'none';
                        if (fields) fields.style.display = 'none';
                        if (bottomFields) bottomFields.style.display = 'none';
                        if (actions) actions.style.display = 'none';
                        if (successMessage) successMessage.style.display = 'block';
                    } else {
                        const mainError = form.querySelector('.form-error-message');
                        if (mainError) {
                            mainError.textContent = result.message || 'Er is een fout opgetreden.';
                            mainError.style.display = 'block';
                        }
                        if (submitBtn) submitBtn.disabled = false;
                        if (btnText) btnText.style.opacity = '1';
                        if (btnSpinner) btnSpinner.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Submit error:', error);
                    const mainError = form.querySelector('.form-error-message');
                    if (mainError) {
                        mainError.textContent = 'Verbindingsfout. Controleer je internetverbinding en probeer het opnieuw.';
                        mainError.style.display = 'block';
                    }
                    if (submitBtn) submitBtn.disabled = false;
                    if (btnText) btnText.style.opacity = '1';
                    if (btnSpinner) btnSpinner.style.display = 'none';
                });
            }
        });
    });
    
    // Verwijder data-contact-form zodat de globale contact-form.js dit formulier negeert
    // en we de custom prototype submit logica kunnen gebruiken.
    const protoForm = document.querySelector('.prototype-cta-block form');
    if (protoForm) protoForm.removeAttribute('data-contact-form');
    </script>
</div>

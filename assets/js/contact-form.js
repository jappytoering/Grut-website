document.addEventListener('DOMContentLoaded', () => {
    const contactForms = document.querySelectorAll('form[data-contact-form]');

    contactForms.forEach(form => {
        // Inline validation on blur
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            // Verberg de error message als de gebruiker weer begint te typen
            input.addEventListener('input', () => clearError(input));
        });

        // Submit handler
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Voorkom dubbele submits
            const submitBtn = form.querySelector('.cta-submit-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnSpinner = submitBtn.querySelector('.btn-spinner');
            
            if (submitBtn.disabled) return;

            // Valideer alle velden voor submit
            let isValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                showFormError(form, 'Vul alle verplichte velden correct in.');
                return;
            }

            // Set loading state
            submitBtn.disabled = true;
            btnText.style.opacity = '0.5';
            btnSpinner.style.display = 'inline-block';
            clearFormError(form);

            try {
                const formData = new FormData(form);
                formData.append('source_url', window.location.href);
                formData.append('browser_timestamp', new Date().toISOString());

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Success state
                    form.querySelector('.cta-form-fields').style.display = 'none';
                    form.querySelector('.cta-form-actions').style.display = 'none';
                    const successMessage = form.querySelector('.cta-success-message');
                    successMessage.style.display = 'block';
                    
                    // Sluit overlay eventueel na paar seconden (indien in overlay)
                    if (form.closest('.contact-overlay')) {
                        setTimeout(() => {
                            const closeBtn = form.closest('.contact-overlay').querySelector('.overlay-close');
                            if (closeBtn) closeBtn.click();
                        }, 3000);
                    }
                } else {
                    // Error state (server validation failed)
                    showFormError(form, result.message || 'Er is een fout opgetreden. Probeer het later opnieuw.');
                }
            } catch (error) {
                console.error('Submit error:', error);
                showFormError(form, 'Verbindingsfout. Controleer je internetverbinding en probeer het opnieuw.');
            } finally {
                // Reset loading state if not successful
                if (form.querySelector('.cta-form-fields').style.display !== 'none') {
                    submitBtn.disabled = false;
                    btnText.style.opacity = '1';
                    btnSpinner.style.display = 'none';
                }
            }
        });
    });

    function validateField(input) {
        // Skip hidden and radio fields for simple validation
        if (input.type === 'hidden' || input.type === 'radio') return true;

        let error = '';
        
        if (input.required && !input.value.trim()) {
            error = 'Dit veld is verplicht.';
        } else if (input.type === 'email' && input.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value.trim())) {
                error = 'Voer een geldig e-mailadres in.';
            }
        }

        const errorContainer = input.closest('.form-group').querySelector('.error-message');
        if (error) {
            errorContainer.textContent = error;
            input.classList.add('has-error');
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function clearError(input) {
        if (input.type === 'hidden' || input.type === 'radio') return;
        const errorContainer = input.closest('.form-group').querySelector('.error-message');
        if (errorContainer) {
            errorContainer.textContent = '';
        }
        input.classList.remove('has-error');
    }

    function showFormError(form, message) {
        const errorDiv = form.querySelector('.form-error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    function clearFormError(form) {
        const errorDiv = form.querySelector('.form-error-message');
        errorDiv.textContent = '';
        errorDiv.style.display = 'none';
    }
});

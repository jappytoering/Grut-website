document.addEventListener('DOMContentLoaded', () => {
    // Zoek alle triggers om overlays te openen
    const overlayTriggers = document.querySelectorAll('[data-overlay-trigger]');
    
    // Sluitknoppen binnen de overlays
    const closeButtons = document.querySelectorAll('.overlay-close, [data-overlay-close]');
    
    let activeOverlay = null;
    let previouslyFocusedElement = null;

    overlayTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = trigger.getAttribute('data-overlay-trigger');
            const targetOverlay = document.getElementById(targetId);
            
            if (targetOverlay) {
                openOverlay(targetOverlay);
            }
        });
    });

    closeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (activeOverlay) {
                closeOverlay(activeOverlay);
            }
        });
    });

    // Sluit overlay als op de achtergrond wordt geklikt (backdrop)
    document.querySelectorAll('.contact-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeOverlay(overlay);
            }
        });
    });

    // Toetsenbord interacties
    document.addEventListener('keydown', (e) => {
        if (!activeOverlay) return;

        // Sluiten met Escape
        if (e.key === 'Escape') {
            closeOverlay(activeOverlay);
        }

        // Focus trap met Tab
        if (e.key === 'Tab') {
            const focusableElements = activeOverlay.querySelectorAll(
                'a[href], button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length === 0) return;

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });

    function openOverlay(overlay) {
        previouslyFocusedElement = document.activeElement;
        activeOverlay = overlay;
        
        // Activeer overlay
        overlay.classList.add('is-active');
        overlay.setAttribute('aria-hidden', 'false');
        
        // Body scroll lock
        document.body.style.overflow = 'hidden';

        // Desktop autofocus op eerste input veld
        if (window.innerWidth > 768) {
            const firstInput = overlay.querySelector('input:not([type="hidden"]), textarea, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    function closeOverlay(overlay) {
        overlay.classList.remove('is-active');
        overlay.setAttribute('aria-hidden', 'true');
        
        document.body.style.overflow = '';
        activeOverlay = null;

        if (previouslyFocusedElement) {
            previouslyFocusedElement.focus();
        }
    }
});

    </main>

    <!-- Global Toast Notification Container -->
    <div id="toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;"></div>

    <script>
    /**
     * Shows a toast notification.
     * @param {string} msg The message to display.
     * @param {string} type 'success', 'error', 'info'
     */
    function showToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        let bgColor = '#10b981'; // success green
        if (type === 'error') bgColor = '#ef4444'; // error red
        if (type === 'info') bgColor = '#3b82f6'; // info blue

        toast.style.background = bgColor;
        toast.style.color = 'white';
        toast.style.padding = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        toast.style.fontFamily = 'var(--font-primary), sans-serif';
        toast.style.fontSize = '14px';
        toast.style.fontWeight = '500';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        toast.style.transition = 'all 0.3s ease';
        toast.innerText = msg;

        container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);

        // Animate out after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => {
                if (container.contains(toast)) {
                    container.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // CSRF Fetch Override removed (handled per-request)

    // Dirty checking for unsaved changes
    window.isDirty = false;
    window.addEventListener('beforeunload', function (e) {
        if (window.isDirty) {
            e.preventDefault();
            e.returnValue = 'Je hebt onopgeslagen wijzigingen. Weet je zeker dat je wilt vertrekken?';
        }
    });
    </script>
</body>
</html>

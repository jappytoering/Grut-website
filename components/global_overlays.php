<!-- Template Modal Mobile (Moved to root for correct z-index stacking) -->
<div class="card-modal" id="mobile-bio-modal">
<div class="card-modal__scroll">
<!-- Javascript inloads here -->
</div>
<div class="card-modal__controls">
<div class="card-slider__dots">
<div class="card-slider__dot active" data-index="0"></div>
<div class="card-slider__dot" data-index="1"></div>
<div class="card-slider__dot" data-index="2"></div>
</div>
</div>
</div>
<!-- End of mobile-bio-modal -->
<!-- Custom Cursor for Bubbels -->
<div class="custom-cursor" id="custom-cursor">
    <div class="custom-cursor-inner">
        <span>Push me</span>
        <span class="custom-cursor-emoji">👇</span>
    </div>
</div>
</script>

<!-- Email Copy Script -->
<script>
function copyEmail(btn) {
    const email = "letsgo@grutdesigners.nl";
    navigator.clipboard.writeText(email).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = `
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>Gekopieerd!</span>
        `;
        btn.style.pointerEvents = "none";
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.pointerEvents = "auto";
        }, 3000);
    });
}

function copyUXTemplate(btn, event) {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth < 768;

    if (!isMobile) {
        // Desktop: Prevent mailto and copy ONLY email
        if (event) event.preventDefault();
        
        const email = "letsgo@grutdesigners.nl";
        navigator.clipboard.writeText(email).then(() => {
            const originalText = btn.innerHTML;
            btn.innerHTML = `
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Email gekopieerd!</span>
            `;
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 3000);
        });
        return;
    }

    // Mobile: Default mailto fires natively, but we also copy full template
    const template = `Aan: letsgo@grutdesigners.nl
Onderwerp: UX-scan aanvragen

Hoi team Grut, 

Ik wil graag dat jullie mijn website onder de loep nemen. Graag plan ik een kennismaking met jullie in zodat we samen de kansen kunnen doornemen. 

Het adres van mijn website is: [URL]
Mijn telefoonnummer is: [Telefoonnummer]
Ik heb veel tijd in week: [Weeknummer]

Met vriendelijke groet, 

[Je naam]`;
    
    navigator.clipboard.writeText(template).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = `
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>Done</span>

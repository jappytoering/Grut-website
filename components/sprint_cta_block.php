<div class="prototype-cta-block">
                <?php 
                $custom_hero = '
                <div class="prototype-hero" style="text-align: center; margin-bottom: 32px;">
                    <p class="prototype-subtitle" style="color: #CBD5E1; font-size: var(--body-size, 16px); margin-bottom: 16px; font-family: var(--font-body); font-weight: 400; letter-spacing: 0.5px;">Heb jij een goede casus?</p>
                    <h2 class="prototype-title" style="font-family: var(--font-heading); font-size: clamp(32px, 5vw, 48px); font-weight: 800; color: #FFFFFF; line-height: 1.1; margin: 0; display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                        Wij 
                        <span class="title-faces" style="display: inline-flex;">
                            <img src="../assets/jappy-rond.webp" alt="Jappy" style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid #0E1726; z-index: 2; position: relative;">
                            <img src="../assets/jurrit-rond.webp" alt="Jurrit" style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid #0E1726; margin-left: -16px; z-index: 1; position: relative;">
                        </span>
                        gaan graag in gesprek
                    </h2>
                </div>';
                
                $custom_success = '
                <div class="prototype-success-message" style="text-align: center;">
                    <p class="prototype-subtitle" style="color: #CBD5E1; font-size: var(--body-size, 16px); margin-bottom: 16px; font-family: var(--font-body); font-weight: 400; letter-spacing: 0.5px;">
                        Bedankt voor je aanvraag
                    </p>
                    <h2 class="prototype-title" style="font-family: var(--font-heading); font-size: clamp(32px, 5vw, 48px); font-weight: 800; color: #FFFFFF; line-height: 1.1; margin: 0; display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                        Wij 
                        <span class="title-faces" style="display: inline-flex;">
                            <img src="../assets/jappy-rond.webp" alt="Jappy" style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid #0E1726; z-index: 2; position: relative;">
                            <img src="../assets/jurrit-rond.webp" alt="Jurrit" style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid #0E1726; margin-left: -16px; z-index: 1; position: relative;">
                        </span>
                        komen ZSM op de lijn
                    </h2>
                </div>';
                
                echo render_cta_block('prototype-sprint', [
                    'custom_header_html' => $custom_hero,
                    'custom_success_html' => $custom_success
                ]); 
                ?>
            </div>
            
            
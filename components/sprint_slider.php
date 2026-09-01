<!-- Hero Auto Slider (replacing static image) -->
                <div class="hero-slider" id="heroAutoSlider">
                    <div class="hero-slider__slides" id="heroSliderSlides">
                        <div class="hero-slider__slide is-active" data-index="0">
                            <img class="hero-slider__img" src="../assets/sprint-slider-1.jpg" alt="Sessie" width="1600" height="1000" fetchpriority="high" decoding="async">
                            <div class="hero-slider__content">
                                <h3 class="hero-slider__title"><?= t('prototype.slider1.title', 'Met Figma, Claude code en Google tools'); ?></h3>
                            </div>
                        </div>
                        <div class="hero-slider__slide" data-index="1">
                            <img class="hero-slider__img zoom-on-mobile" src="../assets/sprint-slider-2.jpg" alt="Snelheid" width="1600" height="1000" loading="lazy" decoding="async">
                            <div class="hero-slider__content">
                                <h3 class="hero-slider__title"><?= t('prototype.slider2.title', 'Van idee naar klikbaar prototype'); ?></h3>
                            </div>
                        </div>
                        <div class="hero-slider__slide" data-index="2">
                            <img class="hero-slider__img" src="../assets/sprint-slider-3.jpg" alt="Resultaat" width="1600" height="1000" loading="lazy" decoding="async">
                            <div class="hero-slider__content">
                                <h3 class="hero-slider__title"><?= t('prototype.slider3.title', 'Validatie bij echte klanten'); ?></h3>
                            </div>
                        </div>
                        <div class="hero-slider__slide" data-index="3">
                            <img class="hero-slider__img" src="../assets/content-afbeelding-3.webp" alt="Brainstorm" width="1600" height="1000" loading="lazy" decoding="async">
                            <div class="hero-slider__content">
                                <h3 class="hero-slider__title"><?= t('prototype.slider4.title', 'Experts met 10 jaar ervaring'); ?></h3>
                            </div>
                        </div>
                        <div class="hero-slider__slide" data-index="4">
                            <img class="hero-slider__img" src="../assets/content-afbeelding-9.webp" alt="Post-its" width="1600" height="1000" loading="lazy" decoding="async">
                            <div class="hero-slider__content">
                                <h3 class="hero-slider__title"><?= t('prototype.slider5.title', 'Onze ervaring gecombineerd met jullie kennis'); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-slider__preview" id="heroSliderPreviewBtn">
                        <img src="" alt="Next" class="hero-slider__preview-img" id="heroSliderNextImg">
                        <svg class="hero-slider__progress" viewBox="0 0 64 64">
                            <circle class="hero-slider__progress-bg" cx="32" cy="32" r="30"></circle>
                            <circle class="hero-slider__progress-ring" id="heroSliderProgress" cx="32" cy="32" r="30"></circle>
                        </svg>
                    </div>
                </div>

                
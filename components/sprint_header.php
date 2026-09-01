<style>
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-200.woff2") format("woff2");
            font-weight: 200;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-300.woff2") format("woff2");
            font-weight: 300;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-400.woff2") format("woff2");
            font-weight: 400;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-500.woff2") format("woff2");
            font-weight: 500;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-600.woff2") format("woff2");
            font-weight: 600;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Clash Grotesk";
            src: url("../assets/fonts/ClashGrotesk-700.woff2") format("woff2");
            font-weight: 700;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Satoshi";
            src: url("../assets/fonts/Satoshi-300.woff2") format("woff2");
            font-weight: 300;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Satoshi";
            src: url("../assets/fonts/Satoshi-400.woff2") format("woff2");
            font-weight: 400;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Satoshi";
            src: url("../assets/fonts/Satoshi-500.woff2") format("woff2");
            font-weight: 500;
            font-display: swap;
            font-style: normal;
        }
        @font-face {
            font-family: "Satoshi";
            src: url("../assets/fonts/Satoshi-700.woff2") format("woff2");
            font-weight: 700;
            font-display: swap;
            font-style: normal;
        }
        /* Specific overrides for standalone overlay page */
        html, body {
            overflow-x: hidden;
            background-color: #0b1120; /* Default fallback dark theme color */
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .standalone-overlay {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .standalone-overlay .overlay-header {
            position: relative;
            padding-top: 40px; /* Extra spacing on standalone */
            pointer-events: auto;
            align-items: center; /* Center align everything vertically */
        }

        .home-btn-transparent {
            background-color: transparent !important;
        }
        .home-btn-transparent:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        @media (min-width: 768px) {
            .home-btn-transparent {
                color: var(--color-yellow) !important;
            }
        }
        .standalone-overlay .overlay-header__tag {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .overlay-content__tags .overlay-header__tag {
            font-size: calc(var(--body-size) - 2px);
            font-weight: 500;
        }
        .standalone-overlay .overlay-content-container {
            margin-top: 0;
        }
        .mobile-text {
            display: none;
        }
        @media (max-width: 768px) {
            .standalone-overlay .overlay-content-container {
                margin-top: 24px;
            }
            .header-logo {
                height: 33px !important;
            }
            .hide-emoji-mobile {
                display: none !important;
            }
            .desktop-text {
                display: none;
            }
            .mobile-text {
                display: inline;
            }
            .square-on-mobile {
                aspect-ratio: 100 / 85 !important;
            }
            .tall-on-mobile {
                aspect-ratio: 100 / 105 !important;
            }
            .zoom-on-mobile {
                transform: scale(1.3);
                transform-origin: 15% 50%;
            }
            .highlight-content {
                padding: 1.5rem 2rem !important;
            }
            .hero-slider__preview {
                width: 40px !important;
                height: 40px !important;
                right: 1.5rem !important;
                bottom: calc(2rem - 1px) !important;
            }
            .overlay-flexible-content .hero-slider__title {
                min-height: 2.4em !important;
                display: flex !important;
                align-items: center !important;
            }
            .hero-slider__preview-img {
                width: 34px !important;
                height: 34px !important;
                top: 3px !important;
                left: 3px !important;
            }
            .hero-slider__progress {
                width: 40px !important;
                height: 40px !important;
            }
            .hero-slider__content {
                padding-right: 4.5rem !important;
            }
            .overlay-flexible-content > h3 {
                font-size: 1.6rem !important;
            }
            .prototype-highlight-block {
                flex-direction: column-reverse !important;
            }
            .day-label {
                min-width: auto !important;
            }
            .day-icon, .prototype-faq-icon {
                width: 26px !important;
                height: 26px !important;
            }
            .day-icon svg, .prototype-faq-icon svg {
                width: 16px !important;
                height: 16px !important;
            }
            .prototype-cta-block .btn {
                width: 100% !important;
                justify-content: center !important;
            }
            .prototype-trusted-block {
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 1.5rem !important;
                margin-bottom: 0 !important;
            }
            .prototype-trusted-logos {
                flex-wrap: nowrap !important;
                gap: 1rem !important;
                min-width: 100% !important;
                justify-content: space-between !important;
            }
            .prototype-trusted-logos .logo-box {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
            }
            .prototype-trusted-logos .logo-box img {
                height: 16px !important;
            }
        }
        /* Override global modal-meta-list for this specific page */
        .modal-meta-list {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 24px !important;
            padding: 1.5rem 2rem !important;
            margin-top: 0 !important;
        }
        
        .prototype-trusted-block {
            text-align: center;
            margin: 4rem 0 3rem 0;
        }
        .prototype-trusted-title {
            margin-bottom: 3rem !important;
            color: #C7CBD1;
            font-size: 1.4rem !important;
            font-family: var(--font-heading) !important;
            font-weight: 500 !important;
        }
        .prototype-trusted-logos {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            width: 100%;
        }
        @media (min-width: 768px) {
            .prototype-trusted-logos {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .prototype-trusted-logos .logo-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem;
            aspect-ratio: 1 / 1;
            border-radius: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .prototype-trusted-logos .logo-box:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.06);
        }
        .prototype-trusted-logos img {
            height: 40px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }

        /* Prototype Sprint Extra Components */
        .prototype-highlight-block {
            display: flex;
            flex-direction: column;
            margin: 3rem 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .prototype-highlight-block {
                flex-direction: row;
                align-items: stretch;
                gap: 0;
            }
        }
        .highlight-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        @media (min-width: 768px) {
            .highlight-content {
                padding: 3rem;
                flex: 1 1 50%;
                max-width: 50%;
            }
            .highlight-image {
                flex: 1 1 50%;
                max-width: 50%;
            }
        }
        .highlight-content h3 {
            margin-top: 0;
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.4rem;
            font-weight: 600;
        }
        .highlight-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .highlight-content li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.25rem;
            color: rgba(255, 255, 255, 0.9);
        }
        .highlight-content li:last-child {
            margin-bottom: 0;
        }
        .highlight-content .check {
            color: var(--color-green, #79D6A2);
            font-weight: bold;
            font-size: 1.2rem;
        }
        .highlight-image {
            flex: 1;
            display: flex;
            min-height: 250px;
        }
        .highlight-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .overlay-content__tags .overlay-header__tag {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .prototype-columns-block {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin: 0 0 3rem 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 24px;
        }
        @media (min-width: 768px) {
            .prototype-columns-block {
                flex-direction: row;
                align-items: stretch;
                gap: 3rem;
                padding: 3rem;
            }
        }
        .prototype-columns-block .column {
            flex: 1;
        }
        .prototype-columns-block .divider {
            display: none;
        }
        @media (min-width: 768px) {
            .prototype-columns-block .divider {
                display: block;
                width: 1px;
                align-self: stretch;
                background: rgba(255, 255, 255, 0.1);
            }
        }
        .prototype-columns-block h4 {
            margin-top: 0;
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.4rem;
            font-weight: 600;
            font-family: var(--font-heading);
        }
        .prototype-columns-block ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .prototype-columns-block li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.5rem;
            font-size: var(--body-size);
            color: rgba(255, 255, 255, 0.9);
        }
        .prototype-columns-block li:last-child {
            margin-bottom: 0;
        }
        .prototype-columns-block .check {
            color: var(--color-green, #79D6A2);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .prototype-days-list {
            margin: 0 0 3rem 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 1.5rem 2rem;
        }
        .day-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
        }
        .day-item:first-child {
            padding-top: 0;
        }
        .day-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .day-item summary {
            cursor: pointer;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        .day-item summary::-webkit-details-marker {
            display: none;
        }
        .day-label-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .day-label-container {
                align-items: center;
                gap: 2rem;
            }
        }
        .day-label {
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: var(--body-size);
            min-width: 50px;
            color: #fff;
            white-space: nowrap;
        }
        .day-divider {
            width: 1px;
            height: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            margin-top: 2px;
        }
        @media (min-width: 768px) {
            .day-divider {
                height: 24px;
                margin-top: 0;
            }
        }
        .day-desc-title {
            font-size: var(--body-size);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        .day-icon {
            background: #fff;
            color: var(--theme-bg, #0b1120);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background-color 0.3s ease;
            flex-shrink: 0;
        }
        .day-item[open] .day-icon {
            transform: rotate(180deg);
            background: #e0e0e0;
        }
        .day-content {
            padding-top: 1rem;
            color: #ffffff;
            line-height: 1.6;
            font-size: var(--body-size);
        }

        .prototype-faq {
            margin: 0 0 3rem 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 1.5rem 2rem;
        }
        .prototype-faq-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
        }
        .prototype-faq-item:first-child {
            padding-top: 0;
        }
        .prototype-faq-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .prototype-faq-item summary {
            font-family: var(--font-heading);
            font-weight: 500;
            font-size: var(--body-size);
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: color 0.2s ease;
        }
        .prototype-faq-item summary:hover {
            color: #fff;
        }
        .prototype-faq-item summary::-webkit-details-marker {
            display: none;
        }
        .prototype-faq-item[open] summary {
            color: #fff;
            margin-bottom: 1rem;
        }
        .prototype-faq-icon {
            background: #fff;
            color: var(--theme-bg, #0b1120);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background-color 0.3s ease;
            flex-shrink: 0;
        }
        .prototype-faq-item[open] .prototype-faq-icon {
            transform: rotate(180deg);
            background: #e0e0e0;
        }
        .prototype-faq-content {
            color: #ffffff;
            line-height: 1.6;
            font-size: var(--body-size);
        }

        /* Photo Slider */
        .prototype-slider-container {
            margin: 3rem 0;
            width: 100%;
        }
        .prototype-slider-header {
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.4rem;
            font-weight: 600;
        }
        .prototype-slider-track {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            cursor: grab;
            padding-bottom: 1rem;
        }
        .prototype-slider-track::-webkit-scrollbar {
            display: none;
        }
        .prototype-slider-track.is-dragging {
            cursor: grabbing;
            scroll-snap-type: none;
            scroll-behavior: auto;
        }
        .prototype-slider-item {
            flex: 0 0 auto;
            scroll-snap-align: start;
            width: 85%;
            max-width: 450px;
            aspect-ratio: 4/3;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background-color: rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease;
        }
        @media (min-width: 768px) {
            .prototype-slider-item {
                width: 60%;
            }
        }
        .prototype-slider-item:hover {
            transform: scale(0.98);
        }
        .prototype-slider-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            pointer-events: none;
        }
        .prototype-slider-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 5rem 1.5rem 1.5rem 1.5rem;
            background: linear-gradient(to top, rgba(11, 17, 32, 1) 0%, rgba(11, 17, 32, 0.7) 60%, rgba(11, 17, 32, 0) 100%);
            color: #fff;
            font-size: var(--body-size);
            font-weight: 500;
            pointer-events: none;
        }

        /* Hero Auto Slider */
        .hero-slider {
            position: relative;
            width: 100%;
            aspect-ratio: 100 / 86;
            border-radius: 24px;
            overflow: hidden;
            margin: 2rem 0;
            background-color: var(--theme-bg, #0b1120);
        }
        @media (min-width: 768px) {
            .hero-slider {
                aspect-ratio: 16/10;
            }
        }
        .hero-slider__slides {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .hero-slider__slide {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            pointer-events: none;
        }
        .hero-slider__slide.is-active {
            opacity: 1;
            pointer-events: auto;
        }
        .hero-slider__slide .hero-slider__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1);
            transition: transform 6s linear;
        }
        .hero-slider__slide.is-active .hero-slider__img {
            transform: scale(1.05);
        }
        .hero-slider__content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 6rem 2rem 2rem 2rem;
            background: linear-gradient(to top, rgba(11, 17, 32, 1) 0%, rgba(11, 17, 32, 0.7) 60%, rgba(11, 17, 32, 0) 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }
        .overlay-flexible-content .hero-slider__title {
            font-family: var(--font-body);
            font-size: var(--body-size);
            font-weight: 500;
            margin: 0;
            line-height: 1.2;
        }
        .hero-slider__top-tag {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 5;
        }
        .hero-slider__top-tag .overlay-header__tag {
            font-size: calc(var(--body-size) - 3px);
        }

        /* Nieuw CTA Blok */
        .prototype-cta-block {
            background-color: #1a1640; /* var(--color-darkblue) approx */
            border-radius: 24px;
            padding: clamp(24px, 4vw, 40px);
            margin-top: 3rem;
            margin-bottom: 2rem;
            color: #ffffff;
        }

        .prototype-cta-grid {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-areas: 
                "header"
                "checklist"
                "button";
            gap: 24px;
            margin-bottom: 32px;
        }

        @media (min-width: 768px) {
            .prototype-cta-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-areas: 
                    "header checklist"
                    "button checklist";
                row-gap: 32px;
                column-gap: 24px;
            }
        }

        .cta-header { grid-area: header; display: flex; flex-direction: column; gap: 8px; }
        .cta-checklist { grid-area: checklist; display: flex; flex-direction: column; gap: 8px; justify-content: center; margin: 0; padding-left: 0; list-style: none; }
        .cta-button { grid-area: button; align-self: end; justify-self: start; }

        .cta-title {
            font-family: var(--font-heading);
            font-size: clamp(34px, 5vw, 44px);
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
        }

        .cta-price {
            color: var(--color-pink);
            font-size: var(--body-size);
            font-weight: 600;
            font-family: var(--font-body);
            line-height: 1.1;
        }

        .cta-check-item {
            font-size: var(--body-size);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
        }

        .cta-btn-yellow {
            background-color: var(--color-yellow);
            color: #02001A;
            border: none;
            font-size: calc(var(--body-size) - 3px);
            font-weight: 500;
        }
        .cta-btn-yellow:hover {
            transform: scale(1.03);
            background-color: #ffd859;
        }

        .cta-footer-info {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .cta-footer-info {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 32px;
            }
        }

        .cta-footer-text {
            font-size: var(--body-size);
            line-height: 1.5;
            color: #C7CBD1;
            max-width: 600px;
        }
        
        /* Preview / Next Slide Thumbnail */
        .hero-slider__preview {
            position: absolute;
            bottom: calc(2rem - 20px);
            right: 2rem;
            width: 64px;
            height: 64px;
            cursor: pointer;
            z-index: 10;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }
        .hero-slider__preview:hover {
            transform: scale(1.05);
        }
        .hero-slider__preview:active {
            transform: scale(0.95);
        }
        .hero-slider__preview-img {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            z-index: 2;
        }
        .hero-slider__progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 64px;
            height: 64px;
            z-index: 3;
            transform: rotate(-90deg); /* Start at top */
        }
        .hero-slider__progress circle {
            fill: none;
            stroke-width: 3;
        }
        .hero-slider__progress-bg {
            stroke: rgba(255, 255, 255, 0.2);
        }
        .hero-slider__progress-ring {
            stroke: #fff;
            stroke-linecap: round;
        }

        /* 13 inch screens & smaller laptops */
        @media (max-width: 1440px) {
            .hero-slider__preview {
                transform: scale(0.75);
                transform-origin: bottom right;
            }
            .hero-slider__preview:hover {
                transform: scale(0.80);
            }
            .hero-slider__preview:active {
                transform: scale(0.70);
            }
            /* Titels boven alinea's 10% groter (basis is vaak 1.4rem of clamp) */
            .standalone-overlay h3[style*="1.4rem"],
            .standalone-overlay h4[style*="1.4rem"] {
                font-size: 1.54rem !important;
            }
        }
        
        /* --- Formulier Styling Refactor (Grut Dark Theme) --- */
        .prototype-cta-block {
            background: rgba(255, 255, 255, 0.03); /* Of #0E1726, let's stick to glassmorphism */
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 40px;
        }

        /* Titel (Aanhef) */
        .prototype-cta-block .cta-title {
            margin-bottom: 0.25rem; /* 50% minder ruimte naar subtitle */
        }
        
        .prototype-cta-block .cta-subtitle {
            color: rgba(255,255,255,0.7);
            font-family: var(--font-body);
            margin-bottom: 2rem;
        }

        /* Grid Layout */
        .prototype-cta-block .cta-form-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 16px; /* row gap 24px, col gap 16px */
            align-items: start;
            width: 100%;
        }
        .prototype-cta-block .form-group {
            grid-column: span 2;
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        .prototype-cta-block .form-group-field-first_name,
        .prototype-cta-block .form-group-field-last_name,
        .prototype-cta-block .form-group-field-email,
        .prototype-cta-block .form-group-field-phone {
            grid-column: span 1;
        }

        /* Typografie Labels */
        .prototype-cta-block .form-group label {
            color: var(--color-yellow, #FFC01D); 
            font-size: calc(var(--body-size, 16px) - 3px); 
            font-weight: 400; 
            text-transform: none;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            font-family: var(--font-body);
        }
        .prototype-cta-block .required-mark {
            color: #FFC01D;
            opacity: 1;
            margin-left: 3px;
        }

        /* Invoervelden */
        .prototype-cta-block .form-group input:not([type="checkbox"]):not([type="radio"]),
        .prototype-cta-block .form-group textarea,
        .prototype-cta-block .form-group select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            color: #FFFFFF;
            padding: 12px 16px;
            font-family: var(--font-body);
            font-size: var(--body-size, 16px);
            font-weight: 400;
            letter-spacing: 0.5px;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .prototype-cta-block .form-group input:-webkit-autofill,
        .prototype-cta-block .form-group input:-webkit-autofill:hover, 
        .prototype-cta-block .form-group input:-webkit-autofill:focus, 
        .prototype-cta-block .form-group textarea:-webkit-autofill,
        .prototype-cta-block .form-group textarea:-webkit-autofill:hover,
        .prototype-cta-block .form-group textarea:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #171c2b inset !important;
            -webkit-text-fill-color: #FFFFFF !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .prototype-cta-block .form-group input::placeholder,
        .prototype-cta-block .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .prototype-cta-block .form-group input:focus,
        .prototype-cta-block .form-group textarea:focus,
        .prototype-cta-block .form-group select:focus {
            outline: none;
            border-color: #FFC01D;
            box-shadow: 0 0 0 3px rgba(255, 192, 29, 0.15);
        }

        /* Validatie statussen */
        .prototype-cta-block .form-group {
            position: relative;
        }

        .prototype-cta-block .form-group.is-valid input,
        .prototype-cta-block .form-group.is-valid textarea {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(34, 197, 94, 0.4) !important;
            padding-right: 44px;
        }

        .prototype-cta-block .form-group.is-valid input:-webkit-autofill,
        .prototype-cta-block .form-group.is-valid input:-webkit-autofill:hover,
        .prototype-cta-block .form-group.is-valid input:-webkit-autofill:focus,
        .prototype-cta-block .form-group.is-valid textarea:-webkit-autofill,
        .prototype-cta-block .form-group.is-valid textarea:-webkit-autofill:hover,
        .prototype-cta-block .form-group.is-valid textarea:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #1e2432 inset !important;
            -webkit-text-fill-color: #FFFFFF !important;
        }

        .prototype-cta-block .form-group.is-invalid input,
        .prototype-cta-block .form-group.is-invalid textarea {
            background: rgba(239, 68, 68, 0.20) !important;
            border-color: rgba(239, 68, 68, 0.6) !important;
        }

        /* Validatie Icoon */
        .prototype-cta-block .valid-icon {
            position: absolute;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform: scale(0.8);
            bottom: 15px;
            right: 14px;
            color: #22C55E;
            width: 20px;
            height: 20px;
        }
        
        .prototype-cta-block .form-group-textarea .valid-icon {
            bottom: auto;
            top: 14px;
            transform: scale(0.8);
        }

        .prototype-cta-block .form-group.is-valid .valid-icon {
            opacity: 1;
            transform: scale(1);
        }
        
        .prototype-cta-block .form-group-textarea.is-valid .valid-icon {
            transform: scale(1);
        }

        .prototype-cta-block .form-group:not(.is-invalid) .error-message {
            display: none;
        }

        /* Checkbox Styling */
        .prototype-cta-block .form-group-checkbox .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px !important;
            cursor: pointer;
            font-size: calc(var(--body-size, 16px) - 3px);
            color: #CBD5E1;
            margin: 0 !important;
            font-family: var(--font-body);
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: none;
            font-weight: 400;
        }
        /* Verberg default checkbox en gebruik custom vinkje */
        .prototype-cta-block .form-group-checkbox input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px !important;
            height: 18px !important;
            min-width: 18px !important;
            min-height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            flex: 0 0 18px !important;
            aspect-ratio: 1 / 1 !important;
            box-sizing: border-box !important;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            margin: 0;
            padding: 0 !important;
            outline: none;
        }
        .prototype-cta-block .form-group-checkbox input[type="checkbox"]:checked {
            background: #FFC01D;
            border-color: #FFC01D;
        }
        .prototype-cta-block .form-group-checkbox input[type="checkbox"]:checked::after {
            content: '';
            width: 4px;
            height: 8px;
            border: solid #0D1525;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-bottom: 2px;
        }
        .prototype-cta-block .form-group-checkbox input[type="checkbox"]:focus {
            box-shadow: 0 0 0 3px rgba(255, 192, 29, 0.15);
        }

        /* Knoppen & Acties */
        .prototype-cta-block .cta-form-actions {
            margin-top: 24px;
            display: flex;
            justify-content: flex-start;
        }
        .prototype-cta-block .cta-submit-btn {
            background: #FFC01D;
            color: #0D1525;
            border-radius: 9999px;
            font-weight: 600;
            padding: 14px 32px;
            font-size: var(--body-size, 16px);
            font-family: var(--font-body);
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .prototype-cta-block .cta-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 192, 29, 0.2);
        }

        /* Casus textarea teller */
        .prototype-cta-block .char-counter {
            font-size: calc(var(--body-size, 16px) - 3px) !important;
            color: #94A3B8 !important;
            margin-top: 6px !important;
            text-align: right !important;
            font-family: var(--font-body);
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* Form validatie meldingen */
        .prototype-cta-block .error-message,
        .prototype-cta-block .form-error-message {
            color: #ef4444;
            font-size: calc(var(--body-size, 16px) - 3px);
            font-family: var(--font-body);
            font-weight: 300;
            letter-spacing: 0.5px;
            margin-top: 6px;
        }

        /* Responsiveness */
        @media (max-width: 639px) {
            .prototype-cta-block .form-group-field-first_name,
            .prototype-cta-block .form-group-field-last_name,
            .prototype-cta-block .form-group-field-email,
            .prototype-cta-block .form-group-field-phone {
                grid-column: span 2;
            }
            .prototype-cta-block {
                padding: 24px;
            }
        }
    </style>
</head>
<div class="prototype-sprint-container theme-darkblue" style="background-color: var(--theme-bg, #0b1120); color: var(--theme-text, #fff);">

    <div class="standalone-overlay">
        <div class="overlay-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <a href="../index.html" aria-label="Terug naar home" style="color: inherit; text-decoration: none; display: flex; align-items: center;">
                    <svg class="header-logo" aria-label="Grut" fill="none" viewbox="0 0 468 253" xmlns="http://www.w3.org/2000/svg" style="height: 45px; width: auto; color: var(--color-yellow);">
                    <path d="M59.685 40.549C62.9054 39.902 66.3405 39.6863 69.5609 39.6863H139.336V79.8039H103.876V86.4902C117.831 86.4902 138.478 91.2353 139.336 118.196C138.907 132.647 134.184 146.02 126.455 157.02C119.585 166.726 110.353 174.49 99.4034 179.667C90.3862 183.98 80.2956 186.353 69.5609 186.353C59.0409 186.353 48.7355 183.98 39.7184 179.667C37.7862 178.804 36.0686 177.725 34.1364 176.647C13.7404 164.569 0 142.137 0 116.471V109.784C0 81.7451 16.3168 57.8039 39.7184 46.5882C45.9445 43.5686 52.6001 41.4118 59.685 40.549ZM99.4034 109.784C99.4034 101.373 96.1829 94.0392 90.8156 88.4314C85.4482 83.0392 77.934 79.8039 69.5609 79.8039C53.2441 79.8039 39.7184 93.1765 39.7184 109.784V116.471C39.7184 122.941 41.8653 128.98 45.5151 133.941C50.8825 141.49 59.685 146.451 69.5609 146.451C77.934 146.451 85.4482 143 90.8156 137.608C96.1829 132.216 99.4034 124.667 99.4034 116.471V109.784Z" fill="currentColor"></path>
                    <path d="M90.8156 204.255C92.1038 202.745 93.3919 201.235 94.4654 199.725H137.404C132.252 220.216 118.082 237.255 99.4034 246.314C90.3862 250.627 80.2956 253 69.5609 253C59.0409 253 48.7355 250.627 39.7184 246.314C21.04 237.255 7.0849 220.216 1.93225 199.725H44.8711C50.2384 207.706 59.2556 213.098 69.5609 213.098C77.934 213.098 85.4482 209.647 90.8156 204.255Z" fill="currentColor"></path>
                    <path d="M214.152 40.1177C215.225 39.902 216.299 39.902 217.372 39.902H267.415V80.0196H217.372C216.299 80.0196 215.225 80.0196 214.152 80.2353C199.123 81.7451 187.53 94.4706 187.53 110V186.569H147.811V110C147.811 99.2157 150.173 89.0784 154.467 80.0196C165.201 57.3726 187.744 41.1961 214.152 40.1177Z" fill="currentColor"></path>
                    <path d="M326.521 116.686V39.902H366.454V116.686C366.454 155.294 335.323 186.569 296.678 186.569C258.248 186.569 227.117 155.294 227.117 116.686V88.6918H266.836V116.686C266.836 133.078 280.361 146.667 296.678 146.667C313.21 146.667 326.521 133.078 326.521 116.686Z" fill="currentColor"></path>
                    <path d="M434.722 139.98C434.722 143.647 437.728 146.667 441.378 146.667H468V186.569H441.378C415.615 186.569 395.004 165.647 395.004 139.98V80.0196H375.037V39.902H395.004V0H434.722V39.902H454.689V80.0196H403.896V86.7059C418.066 86.7059 434.722 87.1373 434.722 106.549C434.722 114.961 434.722 139.98 434.722 139.98Z" fill="currentColor"></path>
                    </svg>
                </a>
                
                <div class="header-title-divider" style="height: 24px; width: 1px; background-color: rgba(255, 255, 255, 0.3);"></div>
                
                <span class="header-title" style="font-family: var(--font-heading); font-size: clamp(1rem, 1.5vw, 1.1rem); font-weight: 500; color: var(--color-yellow);"><?= t('prototype.header.title', 'Prototype sprint'); ?></span>
            </div>
            
            <a href="../index.html" class="overlay-footer-btn overlay-footer-btn--secondary home-btn-transparent" style="text-decoration: none; display: flex; align-items: center; justify-content: center; pointer-events: auto; padding: 0; width: 44px; height: 44px; border-radius: 50%;" aria-label="Home">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                    <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.99 8.994a.75.75 0 1 1-1.06 1.06L12 5.43l-8.46 8.465a.75.75 0 1 1-1.06-1.06l8.99-8.994Z" />
                    <path d="M12 5.432l8.159 8.162c.136.136.215.32.215.513v7.143a.75.75 0 0 1-.75.75H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75v4.5a.75.75 0 0 1-.75.75H4.625a.75.75 0 0 1-.75-.75v-7.143c0-.193.08-.377.215-.513L12 5.432Z" />
                </svg>
            </a>
        </div>
        
        <div class="overlay-content-container">
            
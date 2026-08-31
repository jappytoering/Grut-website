<?php
require_once __DIR__ . '/../includes/form_helper.php';
require_once __DIR__ . '/../includes/content_helper.php';
$config = require __DIR__ . '/../config/contact.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
    
    <title><?= t('prototype.header.title', 'Prototype sprint'); ?> | Grut Designers</title>
    <meta name="description" content="In <?= t('prototype.hero.tag1', '1 week'); ?> van idee naar een door jouw klanten getest product. Maak gebruik van de Adviesvoucherregeling en krijg 50% vergoed."/>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= t('prototype.header.title', 'Prototype sprint'); ?> | Grut Designers"/>
    <meta property="og:description" content="In <?= t('prototype.hero.tag1', '1 week'); ?> van idee naar een door jouw klanten getest product. Maak gebruik van de Adviesvoucherregeling en krijg 50% vergoed."/>
    <meta property="og:image" content="https://grutdesigners.nl/assets/preview-afbeelding-prototype.jpg?v=3"/>
    <meta property="og:url" content="https://grutdesigners.nl/prototype-sprint/"/>
    <meta property="og:type" content="website"/>
    
    <link rel="icon" href="../assets/logo-beeldmerk.svg" type="image/svg+xml"/>
    
    <!-- Fonts -->
    <link rel="preload" href="../assets/fonts/ClashGrotesk-400.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="../assets/fonts/ClashGrotesk-500.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="../assets/fonts/ClashGrotesk-600.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="../assets/fonts/Satoshi-400.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="../assets/fonts/Satoshi-500.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="../assets/fonts/Satoshi-700.woff2" as="font" type="font/woff2" crossorigin/>

    <link rel="stylesheet" href="../style-v43.min.css"/>
    
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
<body class="theme-darkblue" style="background-color: var(--theme-bg, #0b1120); color: var(--theme-text, #fff);">

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
            <div class="overlay-header__tags" style="margin-bottom: 22px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #C7CBD1; font-family: var(--font-body); font-size: calc(var(--body-size) - 3px); font-weight: 400; letter-spacing: 0.5px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    <span>Nieuw</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Sprinttraject</span>
                </div>
            </div>
            <h3 class="overlay-title" style="margin-bottom: 12px;"><?= t('prototype.hero.title', 'In 5 dagen een werkend product'); ?></h3>
            <div class="overlay-flexible-content">
                <p class="overlay-intro"><?= t('prototype.hero.intro', 'In een razendsnel tempo van vijf dagen transformeren we jouw concept in een tastbaar, klikbaar en getest prototype. We halen de ruis weg, focussen op de kern en zorgen dat je al in korte tijd bewijs hebt van wat wel en niet werkt voor jouw doelgroep. Geen eindeloze vergaderingen, maar direct actie en resultaat. Bruist jouw team van de ideeën, maar mis je de vaart om ze echt te testen? Ontdek hieronder hoe we in vijf dagen van concept naar prototype gaan.'); ?></p>
                
                <div class="overlay-content__tags">
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <?= t('prototype.hero.tag1', '1 week'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <?= t('prototype.hero.tag2', 'Jouw kantoor'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        <?= t('prototype.hero.tag3', 'Hackathon'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-yellow);"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M7 5H3"/><path d="M19 17v4"/><path d="M21 19h-4"/></svg>
                        <?= t('prototype.hero.tag4', 'AI powered'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-yellow);"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="12" y1="2" x2="12" y2="6"/></svg>
                        <?= t('prototype.hero.tag5', 'All-in-prijs'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <?= t('prototype.hero.tag6', 'Met echte klanten'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <?= t('prototype.hero.tag7', '80 ontwikkeluren'); ?>
                    </span>
                </div>

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

                <h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.how_it_works.title', 'Hoe het werkt?'); ?></h3>
                <p style="margin-bottom: 1.5rem; color: #ffffff; line-height: 1.6;"><?= t('prototype.how_it_works.intro', 'Samen met jouw team, onze jarenlange ontwikkelervaring en de kracht van generative AI brengen we jouw idee, bij jou op kantoor, in vijf dagen tot leven. Bovendien testen we het gelijk met medewerkers en klanten:'); ?></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: #ffffff; line-height: 1.6;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.how_it_works.item1', 'Gefocust sprinttraject: In 5 dagen gaan we van denkwerk naar een getest en gevalideerd product'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.how_it_works.item2', 'Korte lijnen: we schakelen dagelijks met jouw medewerkers, klanten en belangrijke stakeholders'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.how_it_works.item3', 'Samen sturen: we houden presentatiemomenten om feedback om te zetten naar vervolgstappen'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.how_it_works.item4', 'Echte feedback: we houden interviews met jouw klanten en laten hen het prototype testen.'); ?></li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.how_it_works.item5', 'Tastbaar resultaat: je eindigt met een werkend product én waardevolle nieuwe inzichten'); ?></li>
                </ul>

                <h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.value.title', 'Waarde toevoegen binnen enkele uren'); ?></h3>
                <p style="margin-bottom: 16px;"><?= t('prototype.value.intro', 'We hebben een programma samengesteld waarmee we maximale impact maken op jouw organisatie. Gevormd door onze jarenlange ervaring in developmentteams, combineren we brainstormtechnieken met de SCRUM-werkwijze en de MVP-aanpak. We brengen complexe eisen en belangen snel in kaart, schatten de toegevoegde waarde in en valideren de software direct bij echte eindgebruikers.'); ?></p>

                <h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.customers.title', 'Werken met echte klanten'); ?></h3>
                <p style="margin-bottom: 16px;"><?= t('prototype.customers.intro', 'Je hebt een idee, maar mist de tijd of capaciteit om uit te zoeken of het écht aanslaat. Voordat je investeert in een langdurig ontwikkeltraject, wil je zeker weten of je doelgroep erop zit te wachten. Door het product direct voor te leggen aan jouw klanten, toetsen we razendsnel de waarde en krijgen we inzicht in de wensen van jouw doelgroep. Die feedback verwerken we direct in korte ontwikkelloops naar een optimale versie.'); ?></p>
                <div class="overlay-content__tags" style="margin-bottom: 2rem;">
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <?= t('prototype.customers.tag1', 'Testpanels'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                        <?= t('prototype.customers.tag2', 'Analytics'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        <?= t('prototype.customers.tag3', 'Enquêtes'); ?>
                    </span>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                        <?= t('prototype.customers.tag4', 'Interviews'); ?>
                    </span>
                </div>
                <h3 style="margin-top: 3rem; margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.short_route.title', 'Ons traject in het kort'); ?></h3>
                <div class="modal-meta-list" style="margin-top: 0; margin-bottom: 3rem;">
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Doorlooptijd:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.time', '5 werkdagen'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Aanpak:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.approach', 'Snel van ontwerp naar validatie'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Expertise:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.expertise', 'Brainstorms, UX design, AI development, usertests'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Inzet:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.effort', '80 uur inzet door experts'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Resultaat:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.result', 'Door klant getest product'); ?></span>
                    </div>
                </div>
                <h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.ai_power.title', 'AI-kracht samen met ervaren regisseurs'); ?></h3>
                <p style="margin-bottom: 1.5rem; color: #ffffff; line-height: 1.6;"><?= t('prototype.ai_power.intro', 'Dankzij de combinatie van Figma en moderne AI-development (zoals Claude Code en Antigravity) bouwen we in dagen wat voorheen maanden kostte. Iets in elkaar zetten met AI is tegenwoordig niet zo moeilijk meer; de echte uitdaging zit in structuur, kwaliteit en haalbaarheid. Met onze ontwikkelervaring zorgen we voor een schaalbare architectuur, een behapbare scope en een resultaat dat implementeerbaar is.'); ?></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: #ffffff; line-height: 1.6;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item1', 'Schaalbare design systems: herbruikbare componenten'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item2', 'Veilige code-omgeving: draait op eigen servers'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item3', 'Gestructureerde workflows: strakke kaders en processen'); ?></li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item4', 'Scherpe inventarisatie: overzicht bij complexe belangen'); ?></li>
                </ul>

                <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 3rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <svg width="18" height="24" viewBox="0 0 38 57" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 28.5C19 33.7467 14.7467 38 9.5 38C4.25329 38 0 33.7467 0 28.5C0 23.2533 4.25329 19 9.5 19H19V28.5Z" fill="#0ACF83"/><path d="M0 47.5C0 52.7467 4.25329 57 9.5 57C14.7467 57 19 52.7467 19 47.5V38H9.5C4.25329 38 0 42.2533 0 47.5Z" fill="#1ABCFE"/><path d="M38 9.5C38 14.7467 33.7467 19 28.5 19H19V0H28.5C33.7467 0 38 4.25329 38 9.5Z" fill="#FF7262"/><path d="M0 9.5C0 14.7467 4.25329 19 9.5 19H19V0H9.5C4.25329 0 0 4.25329 0 9.5Z" fill="#F24E1E"/><path d="M38 28.5C38 33.7467 33.7467 38 28.5 38C23.2533 38 19 33.7467 19 28.5C19 23.2533 23.2533 19 28.5 19C33.7467 19 38 23.2533 38 28.5Z" fill="#A259FF"/></svg>
                        <span>Figma Make</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <img src="../assets/claude-logo.png" alt="Claude" style="width: 22px; height: 22px; object-fit: contain;">
                        <span>Claude Code</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <img src="../assets/antigravity-logo.png" alt="Antigravity" style="width: 24px; height: 24px; object-fit: contain;">
                        <span>Antigravity (Google)</span>
                    </div>
                </div>

                <div style="position: relative; width: 100%; border-radius: 16px; overflow: hidden; margin-bottom: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <img src="../assets/content-afbeelding-11.webp" class="tall-on-mobile" alt="Team aan het werk" loading="lazy" decoding="async" width="1600" height="900" style="width: 100%; aspect-ratio: 16/9; display: block; object-fit: cover;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                        <h3 style="color: #ffffff; font-size: 1.8rem; font-weight: 600; font-family: var(--font-heading); margin: 0; line-height: 1.2; max-width: 90%;"><?= t('prototype.highlight1.text', 'Iedereen kan code genereren, maar wij zorgen voor toegevoegde waarde voor jouw klant'); ?></h3>
                    </div>
                </div>

                <h3 style="margin-top: 3rem; margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.deliverables.title', 'Wat kun je verwachten?'); ?></h3>
                <!-- Opsomming blok -->
                <div class="prototype-columns-block">
                    <div class="column">
                        <h4><?= t('prototype.deliverables.col1.title', 'Wat leveren we'); ?></h4>
                        <ul>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item1', 'Concreet actieplan'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item2', 'Klikbaar prototype'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item3', 'Echte gebruikerstesten'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item4', 'Werkende applicatie'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.hero.tag7', '80 ontwikkeluren'); ?></li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                    <div class="column">
                        <h4><?= t('prototype.deliverables.col2.title', 'De waarde voor jou'); ?></h4>
                        <ul>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item1', 'Zekerheid in 5 dagen'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item2', 'Minimaal ontwikkelrisico'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item3', 'Direct klantinzicht'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item4', 'Kennis van MVP-denken'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item5', 'Kennis van AI'); ?></li>
                        </ul>
                    </div>
                </div>

                <h4 style="margin-bottom: 0.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= t('prototype.fit.title', 'Is jouw kwestie geschikt voor de sprintvorm?'); ?></h4>
                <p style="margin: 0.5rem 0 1rem 0; color: #ffffff; line-height: 1.6;"><?= t('prototype.fit.intro', 'De sprint is ideaal voor het valideren van slimme tools en flows: van configurator, rekentool of keuzehulp tot gestroomlijnde onboarding, vernieuwde checkouts en handige AI-features. Twijfel je of jouw idee hier tussen past? <a href="mailto:letsgo@grutdesigners.nl" style="color: var(--color-yellow); text-decoration: underline; text-underline-offset: 4px;">Mail ons</a> jouw idee. In een korte gezamenlijke <a href="mailto:letsgo@grutdesigners.nl" style="color: var(--color-yellow); text-decoration: underline; text-underline-offset: 4px;">haalbaarheidscheck</a> schatten we snel in of het concept geschikt is voor ons programma.'); ?></p>
                <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; color: #ffffff; line-height: 1.6;">
                    <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: #c084fc; flex-shrink: 0;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        <span><?= t('prototype.fit.item1', 'Perfect voor offertetools, onboarding en de digitalisering van processen'); ?></span>
                    </li>
                    <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: #c084fc; flex-shrink: 0;"><path d="M9 3h6"></path><path d="M10 3v10l-4.5 8h13l-4.5-8V3"></path></svg>
                        <span><?= t('prototype.fit.item2', 'We doen altijd een inschatting op haalbaarheid'); ?></span>
                    </li>
                    <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: #c084fc; flex-shrink: 0;"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        <span><?= t('prototype.fit.item3', 'Veel ervaring in het inschatten van ontwikkelscopes'); ?></span>
                    </li>
                </ul>

                <!-- Uitgelicht blok -->
                <div class="prototype-highlight-block">
                    <div class="highlight-content">
                        <h3><?= t('prototype.highlight2.title', '10 jaar ervaring in usertests en softwareanalyses'); ?></h3>
                        <ul>
                            <li><span class="check">✓</span> <?= t('prototype.highlight2.item1', 'Data-analyses'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.highlight2.item2', 'Meerdere testprincipes'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.highlight2.item3', 'Via Teams'); ?></li>
                        </ul>
                    </div>
                    <div class="highlight-image square-on-mobile">
                        <img src="../assets/strategie2-1200x800.webp?v=3" alt="Uitgelicht" loading="lazy" decoding="async" width="1200" height="800">
                    </div>
                </div>

                <h4 style="margin-bottom: 1.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= t('prototype.program.title', 'Programma'); ?></h4>
                <!-- Opsomming van dagen -->
                <div class="prototype-days-list">
                    <details class="day-item">
                        <summary>
                            <div class="day-label-container">
                                <div class="day-label"><?= t('prototype.program.day1.label', 'Dag 1'); ?></div>
                                <div class="day-divider"></div>
                                <div class="day-desc-title"><?= t('prototype.program.day1.title', 'Verkenning & brainstorm'); ?></div>
                            </div>
                            <span class="day-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="day-content">
                            <p><?= t('prototype.program.day1.content', 'We trappen samen af. Wat is de kern van je idee, voor wie lossen we een probleem op en welke cruciale vragen willen we na vijf dagen beantwoord hebben? We bepalen de scope en scherpen de doelstelling aan.'); ?></p>
                        </div>
                    </details>
                    <details class="day-item">
                        <summary>
                            <div class="day-label-container">
                                <div class="day-label"><?= t('prototype.program.day2.label', 'Dag 2'); ?></div>
                                <div class="day-divider"></div>
                                <div class="day-desc-title"><?= t('prototype.program.day2.title', 'Schetsen, ontdekken & bouwen'); ?></div>
                            </div>
                            <span class="day-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="day-content">
                            <p><?= t('prototype.program.day2.content', 'Wij gaan aan de slag achter de schermen. We vertalen de input naar een doordacht UX-concept, schetsen de belangrijkste user flows en selecteren de juiste tech-stack en AI-tools om de bouw te versnellen.'); ?></p>
                        </div>
                    </details>
                    <details class="day-item">
                        <summary>
                            <div class="day-label-container">
                                <div class="day-label"><?= t('prototype.program.day3.label', 'Dag 3'); ?></div>
                                <div class="day-divider"></div>
                                <div class="day-desc-title"><?= t('prototype.program.day3.title', 'Interne validatie & doorontwikkeling'); ?></div>
                            </div>
                            <span class="day-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="day-content">
                            <p><?= t('prototype.program.day3.content', 'Tijd om het idee tot leven te wekken. Met behulp van moderne AI-coding bouwen we in één dag een volledig interactief, klikbaar en werkend prototype op het gekozen device (mobiel of desktop).'); ?></p>
                        </div>
                    </details>
                    <details class="day-item">
                        <summary>
                            <div class="day-label-container">
                                <div class="day-label"><?= t('prototype.program.day4.label', 'Dag 4'); ?></div>
                                <div class="day-divider"></div>
                                <div class="day-desc-title"><?= t('prototype.program.day4.title', 'Usertesting & Advies'); ?></div>
                            </div>
                            <span class="day-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="day-content">
                            <p><?= t('prototype.program.day4.content', 'We leggen het prototype voor aan echte eindgebruikers uit jouw doelgroep. Wat snappen ze meteen? Waar haken ze af? En vooral: lost dit hun probleem écht op? We verzamelen ongefilterde data en reacties.'); ?></p>
                        </div>
                    </details>
                    <details class="day-item">
                        <summary>
                            <div class="day-label-container">
                                <div class="day-label"><?= t('prototype.program.day5.label', 'Dag 5'); ?></div>
                                <div class="day-divider"></div>
                                <div class="day-desc-title"><?= t('prototype.program.day5.title', 'MVP & Next steps'); ?></div>
                            </div>
                            <span class="day-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="day-content">
                            <p><?= t('prototype.program.day5.content', 'We zetten alle testresultaten en data op een rij. Tijdens een interactieve eindpresentatie laten we zien wat werkt, waar kansen liggen en geven we een onderbouwd advies: vol doorontwikkelen, gericht bijsturen of direct stoppen. Uiteraard leveren we de software op zodat jij het kan gebruiken.'); ?></p>
                        </div>
                    </details>
                </div>

                <h4 style="color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600; margin-top: 3rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <?= t('prototype.we_bring.title_start', 'Wat wij'); ?> 
                    <span style="display: inline-flex; align-items: center;">
                        <img src="../assets/jappy-rond.webp" alt="Jappy" loading="lazy" decoding="async" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--background-dark, #0d0f11); margin-right: -10px; position: relative; z-index: 2;">
                        <img src="../assets/jurrit-rond.webp" alt="Jurrit" loading="lazy" decoding="async" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--background-dark, #0d0f11); position: relative; z-index: 1;">
                    </span>
                    <?= t('prototype.we_bring.title_end', 'inbrengen?'); ?>
                </h4>

                <p style="margin-bottom: 1.5rem; color: #ffffff; line-height: 1.6;"><?= t('prototype.we_bring.intro', 'Met Grut haal je meer dan tien jaar hands-on ervaring per persoon in huis op het snijvlak van strategische UX, online marketing en productontwikkeling. We hebben talloze digitale projecten geleid en weten precies hoe je complexe vraagstukken terugbrengt tot de essentie. We combineren diepgaande ontwerpkennis met slimme AI-development om snel, scherp en zonder omwegen echte waarde voor jouw doelgroep te creëren.'); ?></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: #ffffff; line-height: 1.6;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.we_bring.item1', '2 experts met 10 jaar ervaring in softwareontwikkeling'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.we_bring.item2', 'In educatie, bouw, retail en e-commerce'); ?></li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.we_bring.item3', 'Kennis van AI development, UX design en online marketing'); ?></li>
                </ul>
                <div style="position: relative; width: 100%; border-radius: 16px; overflow: hidden; margin-top: 2rem; margin-bottom: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <img src="../assets/werkplek-jurrit.jpg?v=2" class="tall-on-mobile" alt="<?= t('prototype.we_bring.title_start', 'Wat wij'); ?> inbrengen" loading="lazy" decoding="async" width="1600" height="900" style="width: 100%; aspect-ratio: 16/9; display: block; object-fit: cover;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                        <h3 style="color: #ffffff; font-size: 1.8rem; font-weight: 600; font-family: var(--font-heading); margin: 0; line-height: 1.2; max-width: 90%;"><?= t('prototype.highlight3.text', 'Design ambacht in combi met generative AI'); ?></h3>
                    </div>
                </div>

                <h4 style="margin-bottom: 1.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= t('prototype.faq.title', 'Veelgestelde vragen'); ?></h4>
                <!-- FAQ -->
                <div class="prototype-faq">
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q1', 'Is er veel voorkennis nodig van AI?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a1', 'Nee. Jij brengt de kennis in over jouw bedrijf, product en markt. Wij combineren dat met onze expertise in marketing, UX design, development en AI. In de eerste dagen ontdekken we samen de kern van de casus. Daarna gaan we bouwen en scherpen we het concept direct aan met feedback van jouw medewerkers en klanten.'); ?></p>
                        </div>
                    </details>
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q2', 'Wat als het niet afkomt?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a2', 'We bouwen vaste checkmomenten in tijdens de sprint. Mocht blijken dat een idee toch niet haalbaar is, dan kunnen we op tijd bijsturen of het project stopzetten. Ons uitgangspunt is vertrouwen: we geloven sterk in deze werkwijze en zetten alles op alles om in één week een waardevol resultaat neer te zetten. Mocht er iets onvoorziens gebeuren, dan lossen we dat altijd in goed overleg op.'); ?></p>
                        </div>
                    </details>
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q3', 'Kunnen jullie na deze 5 dagen verder helpen?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a3', 'Absoluut. Het geteste prototype is geen eindpunt, maar het fundament. We sluiten de sprint af met een concreet vervolgadvies en een heldere roadmap voor de technische realisatie. Vanuit daar kunnen we het product direct samen met jou doorontwikkelen tot een volwaardige productie-app, of de documentatie, het design system en de code naadloos overdragen aan jouw eigen developmentteam.'); ?></p>
                        </div>
                    </details>
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q4', 'Hebben jullie dit jaar nog tijd?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a4', 'We doen maximaal 20 van deze intensieve trajecten per jaar, zodat we elk project met 100% focus en energie kunnen draaien. We hebben dit jaar nog een beperkt aantal plekken beschikbaar. Daarom doen we vooraf altijd een korte haalbaarheidscheck: we stappen alleen in als we overtuigd zijn van een grote slagingskans.'); ?></p>
                        </div>
                    </details>
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q5', 'Hoeveel tijd ben ik zelf kwijt aan dit project?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a5', 'Reken op ongeveer twee gezamenlijke werksessies van elk een dagdeel. Daarnaast stemmen we 4 tot 5 keer per week een halfuurtje af met jou of een betrokken medewerker, en voeren we korte gesprekken met een aantal klanten. We richten een gedeeld kanaal (zoals WhatsApp of Teams) in om snel data en feedback uit te wisselen. De voorbereiding kost je circa 2 uur. Uiteraard geldt: hoe meer tijd en input jij erin steekt, hoe rijker het eindresultaat.'); ?></p>
                        </div>
                    </details>
                    <details class="prototype-faq-item">
                        <summary><?= t('prototype.faq.q6', 'Wat kost het?'); ?> <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                        <div class="prototype-faq-content">
                            <p><?= t('prototype.faq.a6', 'De investering bedraagt € 4.999,- (excl. btw). Gevestigd in Fryslân? Maak dan gebruik van de <a href="https://www.snn.nl/zakelijke-subsidies/adviesvoucherregeling-fryslan-2025-2027" target="_blank" rel="noopener noreferrer" style="color: var(--color-yellow); text-decoration: underline; text-underline-offset: 4px;">Adviesvoucherregeling Fryslân (SNN)</a> om 50% subsidie te ontvangen, waardoor de sprint je slechts € 2.499,- kost.'); ?></p>
                        </div>
                    </details>
                </div>
                

            </div>
            
            <h4 style="margin-bottom: 1.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= t('prototype.investment.title', 'Investering'); ?></h4>
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; padding: 2rem; margin-bottom: 3rem;">
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">

                    <div>
                        <div style="font-family: var(--font-body); font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.25rem; font-weight: 600;">IN GELD:</div>
                        <div style="font-size: var(--body-size); color: #ffffff; font-weight: 600;"><?= t('prototype.investment.money_val', '€ 4.999,-'); ?></div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.25rem; font-weight: 600;">VOORBEREIDING:</div>
                        <div style="font-size: var(--body-size); color: #ffffff;"><?= t('prototype.investment.prep_val', '1 à 2 uurtjes'); ?></div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.25rem; font-weight: 600;">JOUW TIJD:</div>
                        <div style="font-size: var(--body-size); color: #ffffff;"><?= t('prototype.investment.time_val', 'Enkele dagdelen (in de sprint) hebben we jouw kennis nodig.'); ?></div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); margin-bottom: 0.25rem; font-weight: 600;">BUITENKANS:</div>
                        <div style="font-size: var(--body-size); color: #ffffff;">Krijg met de <a href="https://www.snn.nl/zakelijke-subsidies/adviesvoucherregeling-fryslan-2025-2027" target="_blank" rel="noopener noreferrer" style="color: #ffffff; text-decoration: none;"><span style="text-decoration: underline; text-underline-offset: 4px;">Adviesvoucherregeling</span></a> (aangeboden door SNN) 50% vergoed en betaal maar <span style="color: #3AD17C; font-weight: 600;">€ 2.499,-</span>.</div>
                    </div>
                </div>
            </div>
            <h4 style="margin-bottom: 0.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= t('prototype.doubts.title', 'Twijfel je nog?'); ?></h4>
            <p style="margin-bottom: 16px;">
                <?= t('prototype.doubts.p1', 'We worden altijd enthousiast van ambitieuze plannen en denken graag vrijblijvend met je mee. Wie weet levert een kort gesprek je direct waardevolle nieuwe inzichten op. Stuur een appje of een mailtje naar <a href="mailto:letsgo@grutdesigners.nl" style="color: var(--color-yellow); text-decoration: underline; text-underline-offset: 4px;">letsgo@grutdesigners.nl</a> en we nemen snel contact op.'); ?> 
            </p>
            <p style="margin-bottom: 16px;">
                <?= t('prototype.doubts.p2', '<strong>Ben jij Friese MKB ondernemer?</strong> Maak dan gebruik van de <a href="https://www.snn.nl/zakelijke-subsidies/adviesvoucherregeling-fryslan-2025-2027" target="_blank" rel="noopener noreferrer" style="color: var(--color-yellow); text-decoration: underline; text-underline-offset: 4px;">Adviesvoucherregeling</a> (aangeboden door SNN) voor innovatietrajecten en krijg 50% korting op het traject.'); ?>
            </p>
            <p style="margin-bottom: 16px;">
                <?= t('prototype.doubts.p3', '<strong>Let op: We hebben dit jaar nog capaciteit voor 3 trajecten, dus wees er snel bij 🚀</strong>'); ?>
            </p>

            <!-- Nieuw CTA Blok -->
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
            
            <!-- Trusted By Block -->
            <div class="prototype-trusted-block" style="margin-top: 3rem; display: flex; align-items: center; justify-content: center; width: 100%; flex-wrap: wrap; gap: 2rem;">
                <p style="font-family: var(--font-body); font-size: var(--body-size); color: rgba(255,255,255,0.6); margin: 0; white-space: nowrap;"><?= t('prototype.trusted.title', 'Wij werkten al voor:'); ?></p>
                <div class="prototype-trusted-logos" style="display: flex; align-items: center; justify-content: center; gap: 1.5rem; flex-wrap: wrap;">
                    <div class="logo-box" style="padding: 1rem 1.5rem; aspect-ratio: auto; border-radius: 12px; height: auto;">
                        <img src="../assets/Daklab logo licht.svg" alt="Daklab" loading="lazy" decoding="async" style="height: 24px; width: auto;">
                    </div>
                    <div class="logo-box" style="padding: 1rem 1.5rem; aspect-ratio: auto; border-radius: 12px; height: auto;">
                        <img src="../assets/Logo/Magister/Light.svg" alt="Magister" loading="lazy" decoding="async" style="height: 24px; width: auto;">
                    </div>
                    <div class="logo-box" style="padding: 1rem 1.5rem; aspect-ratio: auto; border-radius: 12px; height: auto;">
                        <img src="../assets/sanoma_logo.svg" alt="Sanoma" loading="lazy" decoding="async" style="height: 24px; width: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mouse effects JS (No smooth scroll or nav logic needed) -->
    <script src="../script.min.js?v=33" defer></script>
    <script src="/assets/js/contact-form.js" defer></script>
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
</body>
</html>

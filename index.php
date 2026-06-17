<!DOCTYPE html>

<html class="light" lang="pt-br"><head></head><body class="font-body-md text-on-surface selection:bg-secondary-container selection:text-on-secondary-container">
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Áster RPG</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;family=Work+Sans:wght@400;600&amp;family=Amita:wght@400;700&amp;family=Fleur+De+Leah&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style id="custom-styles">
        .font-manuscript { font-family: 'Fleur De Leah', cursive; }
        .font-brand { font-family: 'Fleur De Leah', cursive; }
        
        body {
            background-color: #C0B499; /* Secondary Background as per Depth rules */
            scrollbar-width: thin;
            scrollbar-color: #49326B #E9D6C7;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #E9D6C7; }
        ::-webkit-scrollbar-thumb { 
            background: #49326B; 
            border-radius: 4px;
            border: 2px solid #E9D6C7;
        }

        .paper-page {
            background-color: #E9D6C7;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .ink-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #49326B, transparent);
            width: 80%;
            margin: 2rem auto;
            opacity: 0.3;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container": "#feeadb",
                        "outline-variant": "#ccc4d0",
                        "on-surface": "#231a11",
                        "surface-container-low": "#fff1e7",
                        "surface-container-high": "#f8e5d5",
                        "secondary": "#755b00",
                        "primary-fixed": "#eddcff",
                        "secondary-fixed": "#ffdf91",
                        "surface-variant": "#f2dfd0",
                        "primary-fixed-dim": "#d6bafe",
                        "tertiary-fixed-dim": "#f6adff",
                        "on-tertiary-fixed-variant": "#6f257f",
                        "surface-dim": "#ead7c8",
                        "on-primary-container": "#b89dde",
                        "outline": "#7b7580",
                        "on-tertiary-container": "#dd8aea",
                        "tertiary-container": "#651975",
                        "on-secondary-fixed-variant": "#594400",
                        "tertiary-fixed": "#fed6ff",
                        "primary-container": "#49326b",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ebc251",
                        "secondary-container": "#fdd260",
                        "background": "#fff8f5",
                        "on-surface-variant": "#4a454f",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed": "#350041",
                        "on-primary-fixed-variant": "#523b75",
                        "tertiary": "#490058",
                        "on-primary-fixed": "#250d46",
                        "primary": "#321b53",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#f2dfd0",
                        "on-secondary-container": "#745a00",
                        "inverse-primary": "#d6bafe",
                        "surface-bright": "#fff8f5",
                        "inverse-on-surface": "#ffeee1",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed": "#241a00",
                        "surface-container-lowest": "#ffffff",
                        "on-primary": "#ffffff",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "inverse-surface": "#392e24",
                        "surface": "#fff8f5",
                        "surface-tint": "#6b538e",
                        "on-background": "#231a11"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "unit": "8px",
                        "container-max": "1280px",
                        "margin-desktop": "40px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["Courier Prime"],
                        "headline-lg": ["Playfair Display"],
                        "label-caps": ["Work Sans"],
                        "headline-md": ["Playfair Display"],
                        "headline-sm": ["Playfair Display"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.1em", "fontWeight": "600"}],
                        "headline-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-md": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<!-- TopNavBar -->
<header class="bg-primary-container border-b border-on-primary-fixed-variant/20 sticky top-0 z-50">
<div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop h-20 max-w-[1280px] mx-auto">
<h1 class="font-brand text-[44px] text-secondary-container drop-shadow-sm cursor-pointer hover:text-secondary-fixed-dim transition-all duration-300 leading-none">
<img alt="Logo" class="inline-block h-12 w-auto mr-3 align-middle" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/>Áster
</h1>
<a href="paginaLogin.php" class="bg-primary-container text-secondary-container border border-secondary-container px-6 py-2 font-label-caps text-label-caps hover:bg-secondary-container hover:text-primary-container transition-all active:scale-95" onclick="">
                Login
</a>
</div>
</header>
<main class="max-w-[1280px] mx-auto min-h-screen py-12 px-margin-mobile md:px-margin-desktop">
<!-- Main Content "Page" -->
<div class="paper-page p-margin-mobile md:p-margin-desktop rounded-lg shadow-xl overflow-hidden" style="background: rgb(233, 214, 199);">
<!-- Hero Section -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-gutter items-center mb-16">
<div class="rounded-lg overflow-hidden border border-primary/20 shadow-inner">
<img alt="Magical landscape of Áster" class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700" src="https://cdn.cosmos.so/aefca84a-89c5-4b13-8b0e-476a46869fe2?format=jpeg"/>
</div>
<div class="flex flex-col space-y-6">
<h2 class="font-manuscript text-[64px] text-tertiary-container leading-tight">Bem-vindo  à Setra</h2>
<p class="font-body-md text-body-md text-on-surface-variant text-justify">
                        Áster é um RPG de mundo aberto que se passa em uma terra conhecida como Setra. Um lugar que no passado era rico em magia, hoje se encontra em desequilibrio, beirando o colapso e próximo da realização de uma profecia. A campanha principal de Áster acompanha um grupo do governo que buscando uma resposta simples se vê no meio de caos e uma conspiração maior que o imaginado.
                    </p>
<div class="pt-4">
<a href="paginaLogin.php" class="bg-primary-container text-secondary-container font-headline-sm text-headline-sm px-8 py-4 rounded-sm border border-secondary-container/30 hover:shadow-[0_0_15px_rgba(238,197,84,0.3)] transition-all active:scale-95 group">
                            Crie sua primeira ficha
                            <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">auto_fix</span>
</a>
</div>
</div>
</section>
<div class="ink-divider"></div>
<!-- Bento Grid Blocks -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-12">
<!-- Biblioteca de Áster -->
<div class="group bg-[#C0B499]/30 p-8 border border-tertiary/10 rounded-sm hover:border-tertiary/40 transition-all duration-300 cursor-pointer flex flex-col items-center text-center">
<a href="#">
<div class="w-16 h-16 bg-primary-container rounded-full flex items-center justify-center mb-6 shadow-md group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-secondary-container text-4xl" style="font-variation-settings: 'FILL' 1;">menu_book</span>
</div>
<h3 class="font-manuscript text-[48px] text-tertiary-container mb-4">Biblioteca de Áster</h3>
<p class="font-body-md text-on-surface-variant">Explore os contos, mitos e fragmentos perdidos da história de Setra adquirido nas campanhas.</p>
</a>
</div>
<!-- Manual do Mestre -->
<div class="group bg-[#C0B499]/30 p-8 border border-tertiary/10 rounded-sm hover:border-tertiary/40 transition-all duration-300 cursor-pointer flex flex-col items-center text-center">
<a href="#">
<div class="w-16 h-16 bg-primary-container rounded-full flex items-center justify-center mb-6 shadow-md group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-secondary-container text-4xl" style="font-variation-settings: 'FILL' 1;">theater_comedy</span>
</div>
<h3 class="font-manuscript text-[48px] text-tertiary-container mb-4">Manual do Mestre</h3>
<p class="font-body-md text-on-surface-variant">O livro de regras, todas as informações necessárias para que você crie sua própria história dentro do universo</p>
</a>
</div>
</section>
<!-- Secondary Text Content -->
<section class="max-w-3xl mx-auto text-center py-8">
<p class="font-body-md text-on-surface-variant italic mb-6">
                    "Que Áster esteja com você nessa jornada."
                </p>
<div class="flex justify-center space-x-4">
<span class="h-1 w-1 bg-primary rounded-full"></span>
<span class="h-1 w-1 bg-primary rounded-full"></span>
<span class="h-1 w-1 bg-primary rounded-full"></span>
</div>
</section>
</div>
</main>
<!-- Footer -->
<footer class="bg-primary-container border-t border-on-primary-fixed-variant/20">
<div class="flex flex-col md:flex-row justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-unit-gutter max-w-[1280px] mx-auto text-secondary-container">
<div class="mb-4 md:mb-0">
<h4 class="font-brand text-[36px] text-secondary-container leading-none">
<br>
<img alt="Logo" class="inline-block h-8 w-auto mr-2 align-middle"src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/>Áster
</h4>
<br>
</div>
<p class="font-body-md text-[14px] text-center md:text-right text-on-primary-container">
                © 2026 Áster RPG. O uso comercial não é autorizado.
            </p>
<div class="mt-4 md:mt-0">
<a class="text-on-primary-container font-body-md hover:text-secondary-fixed-dim transition-colors duration-200 underline underline-offset-4" href="#">
                    Voltar ao topo
                </a>
</div>
</div>
</footer>
<script>
        // Subtle Atmospheric Effect: Paper Texture Opacity Shift
        document.addEventListener('DOMContentLoaded', () => {
            const paper = document.querySelector('.paper-page');
            paper.addEventListener('mousemove', (e) => {
                const rect = paper.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                paper.style.background = `radial-gradient(circle at ${x}% ${y}%, #E9D6C7 0%, #E2CFB9 100%)`;
            });

            paper.addEventListener('mouseleave', () => {
                paper.style.background = '#E9D6C7';
            });
        });
    </script>
</body></html>

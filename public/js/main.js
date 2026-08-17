// Gestión del Modo Oscuro
const themeToggleBtn = document.getElementById('theme-toggle');
const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

if (themeToggleBtn) {
    // Mostrar el icono correcto al cargar la página
    if (document.documentElement.classList.contains('dark')) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    themeToggleBtn.addEventListener('click', function() {
        // Intercambiar iconos
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // Alternar tema y guardar en localStorage
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
}

// Menú Móvil Colapsable
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// Acordeón de Categorías en Menú Móvil
const mobileCatToggle = document.getElementById('mobile-categories-toggle');
const mobileCatList = document.getElementById('mobile-categories-list');
const mobileCatArrow = document.getElementById('mobile-categories-arrow');

if (mobileCatToggle && mobileCatList) {
    mobileCatToggle.addEventListener('click', (e) => {
        e.preventDefault();
        mobileCatList.classList.toggle('hidden');
        if (mobileCatArrow) {
            mobileCatArrow.classList.toggle('rotate-180');
        }
    });
}

// Acordeón de Subcategorías en Menú Móvil
const mobileSubToggles = document.querySelectorAll('.mobile-sub-toggle');
mobileSubToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const subList = toggle.nextElementSibling;
        const arrow = toggle.querySelector('.mobile-sub-arrow');
        if (subList) {
            subList.classList.toggle('hidden');
        }
        if (arrow) {
            arrow.classList.toggle('rotate-180');
        }
    });
});

var darkMode = false;

if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    toggleDarkMode()
}

function toggleDarkMode() {
    if(darkMode === false){
        darkMode = true;
        document.documentElement.style.setProperty('--bg', 'var(--bg-dark)');
        document.documentElement.style.setProperty('--primary-bg', 'var(--primary-bg-dark)');
        document.documentElement.style.setProperty('--primary-color', 'var(--primary-color-dark)');
    } else {
        darkMode = false
        document.documentElement.style.setProperty('--bg', 'var(--bg-light)');
        document.documentElement.style.setProperty('--primary-bg', 'var(--primary-bg-light)');
        document.documentElement.style.setProperty('--primary-color', 'var(--primary-color-light)');
    }
}
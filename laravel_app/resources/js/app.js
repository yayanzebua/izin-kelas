// Theme Management
window.initTheme = function() {
    const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

window.toggleTheme = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Add transitioning class to allow for smooth CSS transitions
    document.documentElement.classList.add('theme-transitioning');
    setTimeout(() => {
        document.documentElement.classList.remove('theme-transitioning');
    }, 500);

    return isDark;
};

// Initialize theme immediately
window.initTheme();

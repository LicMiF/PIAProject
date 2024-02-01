
window.addEventListener('beforeunload', function () {
    const currentScrollPosition = window.scrollY || window.pageYOffset;
    localStorage.setItem('scrollPosition', currentScrollPosition);
});

document.addEventListener('DOMContentLoaded', function () {
    const storedScrollPosition = localStorage.getItem('scrollPosition');
    if (storedScrollPosition !== null) {
        window.scrollTo(0, parseInt(storedScrollPosition, 10));
    }
});
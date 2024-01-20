
$(document).ready(function() {
    $('#commentFormId').submit(function(event) {
        const scrollPosition = window.scrollY || window.pageYOffset;
        sessionStorage.setItem('scrollPosition', scrollPosition.toString());
    });

    const storedScrollPosition = sessionStorage.getItem('scrollPosition');
    if (storedScrollPosition !== null) {
        window.scrollTo(0, parseInt(storedScrollPosition, 10));
    }
});
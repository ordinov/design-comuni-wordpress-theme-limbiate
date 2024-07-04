document.addEventListener('DOMContentLoaded', function() {
    const stickyHeader = document.getElementById('sticky-header');

    function toggleStickyHeader() {
        if (window.scrollY > 0) {
            console.log('oooo')
            stickyHeader.classList.remove('sticky-hidden');
        } else {
            stickyHeader.classList.add('sticky-hidden');
        }
    }

    window.addEventListener('scroll', toggleStickyHeader);

    // Initial check in case the page is loaded with a scroll offset
    toggleStickyHeader();
});
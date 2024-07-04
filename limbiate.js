// sticky header
document.addEventListener('DOMContentLoaded', function () {
    const stickyHeader = document.getElementById('sticky-header');
    function toggleStickyHeader() {
        if (window.scrollY > 200) {
            stickyHeader.classList.remove('sticky-hidden');
        } else {
            stickyHeader.classList.add('sticky-hidden');
        }
    }
    window.addEventListener('scroll', toggleStickyHeader);
    toggleStickyHeader();
});

// dropdown menus
document.addEventListener('DOMContentLoaded', function () {
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('mouseenter', function () {
            this.querySelector('.dropdown-menu').style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function () {
            this.querySelector('.dropdown-menu').style.display = 'none';
        });
    });
});
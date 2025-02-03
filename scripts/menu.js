document.addEventListener("DOMContentLoaded", function() {
    const menus = document.querySelectorAll('.menu > li');

    menus.forEach(menuItem => {
        menuItem.addEventListener('mouseenter', function(event) {
            const target = event.target;
            const submenu = target.querySelector('.submenu');
            if (submenu) {
                submenu.style.display = 'block';
            }
        }
        );

        menuItem.addEventListener('mouseleave', function(event) {
            const target = event.target;
            const submenu = target.querySelector('.submenu');
            if (submenu) {
                submenu.style.display = 'none';
            }
        }
        );
    }
    );
}
);
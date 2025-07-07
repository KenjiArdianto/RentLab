document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('transaction-toggle');
    if (toggle) {
        const links = toggle.querySelectorAll('.nav-link');
        const slider = toggle;

        const moveSlider = (element) => {
            const parentRect = toggle.getBoundingClientRect();
            const rect = element.getBoundingClientRect();
            const left = (rect.left - parentRect.left);
            const width = rect.width;
            slider.style.setProperty('--slider-left', `${left}px`);
            slider.style.setProperty('--slider-width', `${width}px`);
        };

        const activeLink = toggle.querySelector('.nav-link.active');
        if (activeLink) {
            setTimeout(() => moveSlider(activeLink), 50);
        }

        links.forEach(link => {
            link.addEventListener('click', function (e) {
                // Hentikan link dari pindah halaman
                e.preventDefault(); 
                
                // Pindahkan kelas 'active'
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Gerakkan slider
                moveSlider(this);
            });
        });
    }
});

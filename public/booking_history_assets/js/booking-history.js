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
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                moveSlider(this);
            });
        });
    }
    flatpickr(".date-picker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
    });

    document.querySelectorAll('.review-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const generalErrorDiv = form.querySelector('.general-error');

            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            generalErrorDiv.classList.add('d-none');

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 422) { 
                    if (body.errors) {
                        for (const field in body.errors) {
                            const errorElement = form.querySelector(`.invalid-feedback[data-field="${field}"]`);
                            const inputElement = form.querySelector(`[name="${field}"]`);
                            
                            if (inputElement) inputElement.classList.add('is-invalid');
                            if (errorElement) errorElement.textContent = body.errors[field][0];
                            if (field === 'rating') {
                                const ratingContainer = form.querySelector('.rating');
                                if (ratingContainer) {
                                    ratingContainer.classList.add('is-invalid');
                                }
                            }
                        }
                    }
                } else if (status === 200 && body.success) { 
                    alert(body.message);
                    window.location.reload();
                } else {
                    generalErrorDiv.textContent = body.message || 'An unexpected error occurred.';
                    generalErrorDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Submit Error:', error);
                generalErrorDiv.textContent = 'A network error occurred. Please try again.';
                generalErrorDiv.classList.remove('d-none');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Submit Review';
            });
        });
    });
});
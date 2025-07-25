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

    // Kode ini akan menargetkan semua form dengan class 'review-form'
    document.querySelectorAll('.review-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            // 1. Mencegah form dikirim secara normal (agar halaman tidak refresh)
            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const generalErrorDiv = form.querySelector('.general-error');

            // Reset tampilan error sebelum mengirim
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            generalErrorDiv.classList.add('d-none');

            // 2. Kirim data form ke server menggunakan AJAX (Fetch API)
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    // Ambil token CSRF dari halaman
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
                },
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                // 3. Tangani respon dari server
                if (status === 422) { // 422 adalah kode untuk error validasi
                    // Tampilkan pesan error di bawah setiap input yang salah
                    if (body.errors) {
                        for (const field in body.errors) {
                            const errorElement = form.querySelector(`.invalid-feedback[data-field="${field}"]`);
                            const inputElement = form.querySelector(`[name="${field}"]`);
                            
                            if (inputElement) inputElement.classList.add('is-invalid');
                            if (errorElement) errorElement.textContent = body.errors[field][0];
                        }
                    }
                } else if (status === 200 && body.success) { // Jika sukses
                    alert(body.message); // Tampilkan pesan sukses
                    window.location.reload(); // Muat ulang halaman untuk melihat perubahan
                } else { // Error lain (misal: 403 Unauthorized)
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
                // Kembalikan tombol ke keadaan semula setelah selesai
                submitButton.disabled = false;
                submitButton.innerHTML = 'Submit Review';
            });
        });
    });

    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');

    flatpickr("#date_range_picker", {
        mode: "range", // Mengaktifkan mode rentang tanggal
        dateFormat: "d M Y", // Format tampilan tanggal (e.g., 14 Jul 2025)
        altInput: true, // Membuat input palsu yang lebih cantik (ini opsional tapi bagus)
        altFormat: "d M Y", // Format yang ditampilkan di input palsu
        
        // Mengisi kembali nilai kalender jika ada pencarian sebelumnya
        defaultDate: [dateFromInput.value, dateToInput.value],

        // Fungsi yang akan dijalankan saat pengguna selesai memilih rentang
        onClose: function(selectedDates) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0];
                const endDate = selectedDates[1];

                // Format tanggal agar sesuai untuk dikirim ke backend (YYYY-MM-DD)
                const formatDate = (date) => {
                    const d = new Date(date);
                    const year = d.getFullYear();
                    const month = ('0' + (d.getMonth() + 1)).slice(-2);
                    const day = ('0' + d.getDate()).slice(-2);
                    return `${year}-${month}-${day}`;
                };

                // Isi nilai input tersembunyi yang akan dikirim ke controller
                dateFromInput.value = formatDate(startDate);
                dateToInput.value = formatDate(endDate);
            }
        }
    });
});

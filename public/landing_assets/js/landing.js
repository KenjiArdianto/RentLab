document.addEventListener('DOMContentLoaded', function() {
    const vehicle_toggle = document.getElementById('vehicle_toggle');
    const jenis_kendaraan_input = document.getElementById('jenis_kendaraan_input');

    if (vehicle_toggle && jenis_kendaraan_input) {
        function update_vehicle_selection() {
            jenis_kendaraan_input.value = vehicle_toggle.checked ? 'mobil' : 'motor';
        }
        vehicle_toggle.addEventListener('change', update_vehicle_selection);
        update_vehicle_selection();
    }

    const tanggal_mulai_input = document.getElementById('tanggal_mulai');
    const tanggal_selesai_input = document.getElementById('tanggal_selesai');
    const cari_sekarang_btn = document.getElementById('cari_sekarang_btn');
    const today = new Date();

    function check_tanggal_valid() {
        if (tanggal_mulai_input && tanggal_selesai_input && cari_sekarang_btn) {
            if (tanggal_mulai_input.value && tanggal_selesai_input.value) {
                cari_sekarang_btn.removeAttribute('disabled');
            } else {
                cari_sekarang_btn.setAttribute('disabled', 'disabled');
            }
        }
    }

    if (tanggal_mulai_input && tanggal_selesai_input) {
        const tanggal_selesai_picker = flatpickr(tanggal_selesai_input, {
            dateFormat: "d/m/Y",
            minDate: today,
            onChange: check_tanggal_valid
        });

        flatpickr(tanggal_mulai_input, {
            dateFormat: "d/m/Y",
            minDate: today,
            onChange: function(selectedDates) {
                if (selectedDates[0]) {
                    tanggal_selesai_picker.set("minDate", selectedDates[0]);
                }
                check_tanggal_valid();
            }
        });
        check_tanggal_valid();
    }
    const testimonial_carousel = document.querySelector('#testimonialCarousel');
    if (testimonial_carousel) {
        new bootstrap.Carousel(testimonial_carousel, {
            interval: 5000,
            wrap: true
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {

    function checkDates() {
        const startDateValue = document.getElementById('startBookDate').value;
        const endDateValue = document.getElementById('endBookDate').value;
        const searchButton = document.getElementById('searchNowBtn');

        if (startDateValue && endDateValue) {
            searchButton.removeAttribute('disabled');
        } else {
            searchButton.setAttribute('disabled', 'true');
        }
    }

    const endDatePicker = flatpickr("#endBookDate", {
        altInput: true,
        static: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onReady: function(selectedDates, dateStr, instance) {
            setTimeout(function() {
                if (instance.altInput) {
                    instance.altInput.placeholder = instance.element.placeholder;
                }
            }, 10);
        },
        onChange: function(selectedDates, dateStr, instance) {
            checkDates();
        }
    });

    flatpickr("#startBookDate", {
        altInput: true,
        static: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onReady: function(selectedDates, dateStr, instance) {
            setTimeout(function() {
                if (instance.altInput) {
                    instance.altInput.placeholder = instance.element.placeholder;
                }
            }, 10);
        },
        onChange: function(selectedDates, dateStr, instance) {
            checkDates();
            if (selectedDates[0]) {
                endDatePicker.set('minDate', selectedDates[0]);
                if (endDatePicker.selectedDates[0] < selectedDates[0]) {
                    endDatePicker.clear();
                }
            }
        }
    });

    const vehicleToggle = document.getElementById('vehicleToggle');
    const vehicleTypeInput = document.getElementById('vehicleTypeInput');

    vehicleToggle.addEventListener('change', function() {
        if (this.checked) {
            vehicleTypeInput.value = 'car';
        } else {
            vehicleTypeInput.value = 'motorcycle';
        }
    });

    checkDates();
});
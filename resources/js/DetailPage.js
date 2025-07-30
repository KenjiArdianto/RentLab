
        $(document).ready(function() {
            const MAX_DATE_RANGES = 3; // Define the maximum number of date ranges
            let selectedDateRanges = []; // Stores objects like { startDate: Date, endDate: Date }
            let currentSelectedStartDate = null;
            let currentSelectedEndDate = null;

            const dateInput = $('#dateInput');
            const cartDisplay = $('#cartDisplay');
            const noDatesMessage = $('#noDatesMessage');
            const datePickerModalInstance = new bootstrap.Modal(document.getElementById('datePickerModal'));

            // Initialize the date picker
            $('#dateRangePicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: false,
                todayHighlight: true,
                toggleActive: true,
                orientation: "auto",
                clearBtn: true,
                maxViewMode: 2,
                multidate: true,
                multidateSeparator: ' - ',
                range: true
            }).on('changeDate', function(e) {
                if (e.dates && e.dates.length > 0) {
                    e.dates.sort((a, b) => a - b); // Ensure dates are sorted

                    currentSelectedStartDate = e.dates[0];
                    if (e.dates.length === 2) {
                        currentSelectedEndDate = e.dates[1];
                        dateInput.val(`${formatDate(currentSelectedStartDate)} - ${formatDate(currentSelectedEndDate)}`);
                    } else {
                        // For single date selection, treat end date as same as start date
                        currentSelectedEndDate = currentSelectedStartDate;
                        dateInput.val(`${formatDate(currentSelectedStartDate)} - ${formatDate(currentSelectedEndDate)}`); // Display as X - X
                    }
                } else {
                    currentSelectedStartDate = null;
                    currentSelectedEndDate = null;
                    dateInput.val('No date range selected');
                }
            });

            // Function to format Date object to YYYY-MM-DD string
            function formatDate(date) {
                if (!date) return '';
                const d = new Date(date);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Function to generate an array of all dates within previously selected ranges for muting
            function getDatesToMute() {
                let datesToMute = [];
                selectedDateRanges.forEach(range => {
                    let currentDate = new Date(range.startDate.getFullYear(), range.startDate.getMonth(), range.startDate.getDate());
                    let endDate = new Date(range.endDate.getFullYear(), range.endDate.getMonth(), range.endDate.getDate());

                    while (currentDate <= endDate) {
                        datesToMute.push(new Date(currentDate)); // Push a copy
                        currentDate.setDate(currentDate.getDate() + 1);
                    }
                });
                return datesToMute;
            }

            // Apply muting to previously selected dates in the calendar
            function muteDatesInCalendar() {
                const datesToDisable = getDatesToMute();
                $('#dateRangePicker').datepicker('setDatesDisabled', datesToDisable);
            }

            // Show the date picker modal when calendar icon is clicked
            $('#calendarIcon').on('click', function() {
                // Check if max ranges reached
                if (selectedDateRanges.length >= MAX_DATE_RANGES) {
                    alert(`You can only select up to ${MAX_DATE_RANGES} date ranges.`);
                    return; // Prevent opening the modal
                }

                dateInput.val('No date range selected'); // Reset display input
                currentSelectedStartDate = null;
                currentSelectedEndDate = null;
                $('#dateRangePicker').datepicker('clearDates'); // Clear any previous selection in the picker
                muteDatesInCalendar(); // Apply muting
                datePickerModalInstance.show(); // Show the modal
            });

            // Save dates when "Save" button in modal is clicked
            $('#saveDatesBtn').on('click', function() {
                if (currentSelectedStartDate && currentSelectedEndDate) {
                    // Check if max ranges reached before saving
                    if (selectedDateRanges.length >= MAX_DATE_RANGES) {
                        alert(`You have already selected ${MAX_DATE_RANGES} date ranges. Please remove one to add more.`);
                        datePickerModalInstance.hide(); // Close modal if max reached
                        return;
                    }

                    // Ensure dates are chronologically ordered (datepicker usually handles this, but good to be safe)
                    if (currentSelectedStartDate > currentSelectedEndDate) {
                        [currentSelectedStartDate, currentSelectedEndDate] = [currentSelectedEndDate, currentSelectedStartDate];
                    }

                    // Check for overlap with existing ranges before adding
                    const isOverlapping = selectedDateRanges.some(existingRange => {
                        // Normalize dates to start of day for accurate comparison
                        const existingStart = new Date(existingRange.startDate.getFullYear(), existingRange.startDate.getMonth(), existingRange.startDate.getDate());
                        const existingEnd = new Date(existingRange.endDate.getFullYear(), existingRange.endDate.getMonth(), existingRange.endDate.getDate());
                        const newStart = new Date(currentSelectedStartDate.getFullYear(), currentSelectedStartDate.getMonth(), currentSelectedStartDate.getDate());
                        const newEnd = new Date(currentSelectedEndDate.getFullYear(), currentSelectedEndDate.getMonth(), currentSelectedEndDate.getDate());

                        return (newStart <= existingEnd && newEnd >= existingStart);
                    });

                    if (isOverlapping) {
                        alert('The selected date range overlaps with a previously selected range. Please choose non-overlapping dates.');
                        return; // Prevent saving overlapping dates
                    }

                    selectedDateRanges.push({
                        startDate: new Date(currentSelectedStartDate), // Store copies of dates
                        endDate: new Date(currentSelectedEndDate)
                    });

                    // Sort ranges by start date for cleaner display and easier future checks
                    selectedDateRanges.sort((a, b) => a.startDate - b.startDate);

                    datePickerModalInstance.hide();
                    updateCartDisplay(); // Update the cart immediately after saving
                } else {
                    alert('Please select both a start and an end date.');
                }
            });

            // "Add to Cart" button: This now primarily triggers a cart display update
            $('#addToCartBtn').on('click', function() {
                if (selectedDateRanges.length === 0) {
                    alert("No dates have been selected yet. Click the calendar icon to add dates.");
                } else {
                    updateCartDisplay(); // Just ensures the cart display is current
                    // In a real application, this button might trigger sending data to a server.
                }
            });

            // Update the cart display and attach delete event listeners
            function updateCartDisplay() {
                cartDisplay.empty(); // Clear existing cart items

                if (selectedDateRanges.length === 0) {
                    cartDisplay.append('<p id="noDatesMessage" class="text-muted">No dates selected yet.</p>');
                } else {
                    // Remove the no dates message if it exists
                    if (noDatesMessage.length) {
                        noDatesMessage.remove();
                    }

                    selectedDateRanges.forEach((range, index) => {
                        const start = formatDate(range.startDate);
                        const end = formatDate(range.endDate);
                        const dateRangeText = `${start} - ${end}`; // Format as startdate - enddate

                        // Append new cart item with a delete button
                        cartDisplay.append(`
                            <div class="cart-item" data-index="${index}">
                                <p class="mb-0"><strong>Selection ${index + 1}:</strong> ${dateRangeText}</p>
                                <button type="button" class="btn btn-danger btn-sm delete-cart-item" data-index="${index}" aria-label="Delete Selection ${index + 1}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H9.5a1 1 0 0 1 1 1v1H14a1 1 0 0 1 1 1v1zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </div>
                        `);
                    });

                    // Attach click handler for delete buttons using event delegation
                    // Use .off() to prevent multiple handlers if updateCartDisplay is called multiple times
                    cartDisplay.off('click', '.delete-cart-item').on('click', '.delete-cart-item', function() {
                        const indexToDelete = $(this).data('index');

                        // Remove the item from the array
                        selectedDateRanges.splice(indexToDelete, 1);

                        // Re-render the cart display and re-apply muting
                        updateCartDisplay();
                        muteDatesInCalendar(); // Update calendar disabled dates
                    });
                }
            }

            // Initial call to update cart display
            updateCartDisplay();
        });


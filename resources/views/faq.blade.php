<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Rent Lab</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Link ke file CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('build/assets/CSS/faq.css') }}">

<x-layout>
    <div class="container pt-4 pb-5">
        <h1 class="text-center mb-4">Frequently Asked Questions</h1>

        <form class="d-flex justify-content-start align-items-center mb-4" role="search">
            <div class="p-1 d-flex flex-row justify-content-start align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-4 search-container">
                
                <label for="faqSearch" class="search-icon-label">
                    <img src="{{ asset('build/assets/icons8-search-50.png') }}" alt="" width="20" height="20">
                </label>

                <div class="container-fluid p-0">
                    {{-- PENTING: ID "faqSearch" ditambahkan di sini agar filter JS tetap berfungsi --}}
                    <input id="faqSearch" class="container-fluid border-0 search-input-inner pe-0 ps-3" type="search" placeholder="Search for your question..." aria-label="Search">
                </div>
                
            </div>
        </form>

        <div id="noResults" class="no-results-message">
            No results found. Try a different or more general keyword.
        </div>

        <div class="accordion" id="faqAccordion">

            <!-- Question 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    {{-- FIXED: Added .collapsed class and set aria-expanded to false --}}
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        What types of vehicles are available for rent at Rent Lab?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Rent Lab offers a variety of vehicles including:
                        <ul>
                            <li>Sedan</li>
                            <li>MPV (Multi-Purpose Vehicle)</li>
                            <li>SUV (Sports Utility Vehicle)</li>
                            <li>Luxury cars</li>
                            <li>Motorcycles: Sports, Scooter, and Standard Bikes</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How do I make a reservation for a rental car?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can easily make a reservation through our website or mobile app. Here’s how:
                        <ol>
                            <li>Choose the vehicle type you want to rent.</li>
                            <li>Pick your rental dates and pickup/drop-off locations.</li>
                            <li>Confirm your booking and proceed with payment.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Is a deposit required to rent a vehicle?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, a deposit is required to secure the vehicle. The deposit amount varies based on the vehicle type and rental duration. This will be refunded upon safe return of the vehicle in the same condition.
                    </div>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Can I rent a car without a driver at Rent Lab?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab offers both self-drive rentals and vehicles with a driver. You can choose either option when making your reservation.
                    </div>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Are there any age restrictions for renting a vehicle?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, the minimum age to rent a vehicle at Rent Lab is 21 years old for cars and 18 years old for motorcycles. Renters must also hold a valid driving license.
                    </div>
                </div>
            </div>

            <!-- Question 6 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        How do I know if my rental reservation is confirmed?
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Once your reservation is complete, you will receive an email or SMS confirmation with your booking details. You can also check the status through your Rent Lab account.
                    </div>
                </div>
            </div>

            <!-- Question 7 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        What documents do I need to provide to rent a vehicle?
                    </button>
                </h2>
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        To rent a vehicle, you’ll need:
                        <ul>
                            <li>A valid driver’s license (international licenses accepted).</li>
                            <li>A government-issued ID or passport.</li>
                            <li>A valid credit card for payment and deposit purposes.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Question 8 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        Can I extend my rental period after booking?
                    </button>
                </h2>
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, you can extend your rental period. Simply contact our customer support or update your booking through the app or website at least 24 hours before the original return date.
                    </div>
                </div>
            </div>

            <!-- Question 9 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingNine">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                        Is there a mileage limit on the cars I rent?
                    </button>
                </h2>
                <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Rent Lab provides a certain mileage limit depending on the vehicle type and rental duration. If you exceed the limit, additional fees may apply. You can opt for unlimited mileage for an additional fee at the time of booking.
                    </div>
                </div>
            </div>

            <!-- Question 10 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                        How do I cancel or modify my reservation?
                    </button>
                </h2>
                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can cancel or modify your reservation via the Rent Lab app or website. Cancellations made more than 24 hours before the pick-up time are free of charge. Changes or cancellations within 24 hours may incur a fee.
                    </div>
                </div>
            </div>
            
            <!-- Question 11 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEleven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                        Can I rent a car for a long-term rental?
                    </button>
                </h2>
                <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab offers long-term rental options. You can rent a vehicle for weeks or even months. Please contact our customer support team for custom rental plans and discounts.
                    </div>
                </div>
            </div>

            <!-- Question 12 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwelve">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                        Can I rent a car for a business trip?
                    </button>
                </h2>
                <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab provides vehicles for business trips. You can rent cars or SUVs for meetings, conferences, and corporate events. We also offer special business packages.
                    </div>
                </div>
            </div>

            <!-- Question 13 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThirteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                        Is it possible to rent a car for a road trip?
                    </button>
                </h2>
                <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Absolutely! Rent Lab offers rentals for road trips. You can choose any vehicle that fits your needs and enjoy your journey with peace of mind. Ensure that your rental includes adequate mileage or choose unlimited mileage for extra convenience.
                    </div>
                </div>
            </div>

            <!-- Question 14 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFourteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                        What should I do if I get into an accident with a rental car?
                    </button>
                </h2>
                <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        In case of an accident, please immediately contact Rent Lab's customer support team. We will guide you through the process of reporting the incident and help you file an insurance claim if applicable.
                    </div>
                </div>
            </div>

            <!-- Question 15 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFifteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                        Are pets allowed in rental vehicles?
                    </button>
                </h2>
                <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Pets are allowed in most of our rental vehicles. However, you must ensure that pets do not damage the interior of the car. A cleaning fee may apply if excessive pet hair or dirt is left behind.
                    </div>
                </div>
            </div>

            <!-- Question 16 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSixteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                        How is the rental price calculated?
                    </button>
                </h2>
                <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The rental price is calculated based on the type of vehicle, rental duration, and additional services like a driver or insurance. Discounts may apply for long-term rentals or special promotions.
                    </div>
                </div>
            </div>

            <!-- Question 17 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeventeen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                        Can I rent a car for a special event, such as a wedding?
                    </button>
                </h2>
                <div id="collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="headingSeventeen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab offers special rental packages for events such as weddings, parties, and corporate functions. You can choose from a range of luxury cars and even request a chauffeur for your event.
                    </div>
                </div>
            </div>

            <!-- Question 18 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEighteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                        Can I rent a car for cross-border travel?
                    </button>
                </h2>
                <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, we allow cross-border rentals. However, additional fees and specific terms apply. Please contact our customer service team for more details on international travel options.
                    </div>
                </div>
            </div>

            <!-- Question 19 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingNineteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                        Are there any additional fees for renting a car?
                    </button>
                </h2>
                <div id="collapseNineteen" class="accordion-collapse collapse" aria-labelledby="headingNineteen" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Additional fees may apply for services such as vehicle insurance, GPS, child seats, and additional drivers. Please check the booking details for a breakdown of costs.
                    </div>
                </div>
            </div>

            <!-- Question 20 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwenty">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                        How do I return the rental car?
                    </button>
                </h2>
                <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Return the car to the agreed-upon location on the specified return date and time. If you need to extend the rental period, inform us beforehand. A late fee may apply if the vehicle is returned later than scheduled.
                    </div>
                </div>
            </div>

            <!-- Question 21 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne" aria-expanded="false" aria-controls="collapseTwentyOne">
                        Can I rent a vehicle for less than a day?
                    </button>
                </h2>
                <div id="collapseTwentyOne" class="accordion-collapse collapse" aria-labelledby="headingTwentyOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab offers hourly rental options for short-term needs. Please contact us for availability and pricing.
                    </div>
                </div>
            </div>

            <!-- Question 22 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo" aria-expanded="false" aria-controls="collapseTwentyTwo">
                        Can I rent a car with a chauffeur?
                    </button>
                </h2>
                <div id="collapseTwentyTwo" class="accordion-collapse collapse" aria-labelledby="headingTwentyTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab provides the option to rent a vehicle with a professional chauffeur. This service is available for an additional fee, which can be selected at the time of booking.
                    </div>
                </div>
            </div>

            <!-- Question 23 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree" aria-expanded="false" aria-controls="collapseTwentyThree">
                        Are the cars insured during the rental period?
                    </button>
                </h2>
                <div id="collapseTwentyThree" class="accordion-collapse collapse" aria-labelledby="headingTwentyThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, all rental vehicles at Rent Lab come with standard insurance coverage. However, certain types of damage or negligence may incur additional fees. Please inquire for more details on the insurance options available.
                    </div>
                </div>
            </div>

            <!-- Question 24 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyFour" aria-expanded="false" aria-controls="collapseTwentyFour">
                        How do I report an accident or damage to a rental vehicle?
                    </button>
                </h2>
                <div id="collapseTwentyFour" class="accordion-collapse collapse" aria-labelledby="headingTwentyFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        In case of an accident or vehicle damage, contact Rent Lab’s customer service immediately. Our team will guide you through the next steps, including filing an insurance claim if applicable.
                    </div>
                </div>
            </div>

            <!-- Question 25 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyFive" aria-expanded="false" aria-controls="collapseTwentyFive">
                        What should I do if the rental vehicle breaks down during my trip?
                    </button>
                </h2>
                <div id="collapseTwentyFive" class="accordion-collapse collapse" aria-labelledby="headingTwentyFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        If the vehicle breaks down, please contact Rent Lab immediately. We provide roadside assistance and will either help with repairs or send a replacement vehicle as soon as possible.
                    </div>
                </div>
            </div>

            <!-- Question 26 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentySix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentySix" aria-expanded="false" aria-controls="collapseTwentySix">
                        Can I drive a rental car outside the city or across state lines?
                    </button>
                </h2>
                <div id="collapseTwentySix" class="accordion-collapse collapse" aria-labelledby="headingTwentySix" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, you are allowed to drive Rent Lab vehicles outside the city or across state lines. However, please inform us in advance as certain conditions and additional insurance coverage may apply for long-distance travel.
                    </div>
                </div>
            </div>

            <!-- Question 27 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentySeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentySeven" aria-expanded="false" aria-controls="collapseTwentySeven">
                        Can I change the pick-up or drop-off location for my rental car?
                    </button>
                </h2>
                <div id="collapseTwentySeven" class="accordion-collapse collapse" aria-labelledby="headingTwentySeven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, you can change the pick-up or drop-off location before your rental period starts. Changes must be made at least 24 hours in advance, and additional fees may apply.
                    </div>
                </div>
            </div>

            <!-- Question 28 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyEight" aria-expanded="false" aria-controls="collapseTwentyEight">
                        How can I get a receipt for my car rental?
                    </button>
                </h2>
                <div id="collapseTwentyEight" class="accordion-collapse collapse" aria-labelledby="headingTwentyEight" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        After returning the vehicle, you can request a receipt through your Rent Lab account, or our customer service team can send it to you via email.
                    </div>
                </div>
            </div>

            <!-- Question 29 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwentyNine">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyNine" aria-expanded="false" aria-controls="collapseTwentyNine">
                        Can I rent a car with unlimited mileage?
                    </button>
                </h2>
                <div id="collapseTwentyNine" class="accordion-collapse collapse" aria-labelledby="headingTwentyNine" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, Rent Lab offers vehicles with unlimited mileage for an additional fee. This option is available at the time of booking, allowing you to travel without worrying about mileage limits.
                    </div>
                </div>
            </div>

            <!-- Question 30 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThirty">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirty" aria-expanded="false" aria-controls="collapseThirty">
                        Are there any hidden fees or charges in the rental agreement?
                    </button>
                </h2>
                <div id="collapseThirty" class="accordion-collapse collapse" aria-labelledby="headingThirty" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Rent Lab is transparent about all fees. The total rental cost will include the vehicle price, taxes, and any extra services like insurance or GPS. Please check the rental agreement for any additional charges based on the services you select.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <script>
        // Simple search filter for FAQ
        document.getElementById('faqSearch').addEventListener('input', function(event) {
            let searchTerm = event.target.value.toLowerCase();
            let faqItems = document.querySelectorAll('.accordion-item');
            let noResultsMessage = document.getElementById('noResults');
            let resultsFound = false;
            
            faqItems.forEach(function(item) {
                let question = item.querySelector('.accordion-button').innerText.toLowerCase();
                if (question.includes(searchTerm)) {
                    item.style.display = 'block';
                    resultsFound = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show the "No results found" message if no results are found
            if (!resultsFound) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        });
    </script>
</x-layout>
</html>
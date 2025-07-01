{{-- ================================================================= --}}
{{--                      TESTIMONIALS SECTION                       --}}
{{-- ================================================================= --}}
<div id='slider'>
    <section class="testimonial_section py-5">
        <div class="container">
            <h2 class="section_title text-center">What Our Customers Say</h2>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    {{-- First Testimonial Item --}}
                    <div class="carousel-item active">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <div class="testimonial_avatar">
                                    <img src="{{ asset('build/assets/images/FikriProfile.png') }}" alt="Fikri Luhur Pangestu">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="testimonial_text">
                                    <span class="quote_mark d-block text-start">“</span>
                                    <p>Renting a car at Rentlab was truly satisfying! The website is very user-friendly, there's a wide selection of cars, and the service is friendly and responsive. I didn't expect the rental process to be this easy.</p>
                                    <p class="author">Fikri Luhur Pangestu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Second Testimonial Item --}}
                    <div class="carousel-item">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <div class="testimonial_avatar">
                                    <img src="{{ asset('build/assets/images/SitiProfile.png') }}" alt="Siti Aminah">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="testimonial_text">
                                    <span class="quote_mark d-block text-start">“</span>
                                    <p>My first time renting a motorcycle here and I was very impressed. The bike was in excellent and clean condition. The booking and pickup process was very fast. I'll definitely be a regular customer!</p>
                                    <p class="author">Siti Aminah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Third Testimonial Item --}}
                    <div class="carousel-item">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <div class="testimonial_avatar">
                                    <img src="{{ asset('build/assets/images/BudiProfile.png') }}" alt="Budi Santoso">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="testimonial_text">
                                    <span class="quote_mark d-block text-start">“</span>
                                    <p>The rental prices are very competitive and transparent, with no hidden fees. The customer service was also very helpful when I had questions. Highly recommended!</p>
                                    <p class="author">Budi Santoso</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Fourth Testimonial Item --}}
                    <div class="carousel-item">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <div class="testimonial_avatar">
                                    <img src="{{ asset('build/assets/images/DewiProfile.png') }}" alt="Dewi Lestari">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="testimonial_text">
                                    <span class="quote_mark d-block text-start">“</span>
                                    <p>It was a lifesaver for a spontaneous family trip. The car was clean, comfortable, and spacious. The kids were happy, and the journey went smoothly. Thank you, Rentlab!</p>
                                    <p class="author">Dewi Lestari</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fifth Testimonial Item --}}
                    <div class="carousel-item">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <div class="testimonial_avatar">
                                    <img src="{{ asset('build/assets/images/AgusProfile.png') }}" alt="Agus Wijaya">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="testimonial_text">
                                    <span class="quote_mark d-block text-start">“</span>
                                    <p>I rented for business purposes for a week. The rental extension process was easy and the service was professional. The vehicle was very reliable. Recommended!</p>
                                    <p class="author">Agus Wijaya</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- Carousel Navigation Buttons --}}
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>
</div>
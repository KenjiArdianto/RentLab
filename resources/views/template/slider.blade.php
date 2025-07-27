<div id='slider'>
    <section class="testimonial_section py-5">
        <div class="container">
            <h2 class="section_title text-center">{{ __('landing.testimonials_title') }}</h2>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

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
                                    <p>{{ __('landing.testimonial_1_text') }}</p>
                                    <p class="author">{{ __('landing.testimonial_1_author') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <p>{{ __('landing.testimonial_2_text') }}</p>
                                    <p class="author">{{ __('landing.testimonial_2_author') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                    <p>{{ __('landing.testimonial_3_text') }}</p>
                                    <p class="author">{{ __('landing.testimonial_3_author') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                    <p>{{ __('landing.testimonial_4_text') }}</p>
                                    <p class="author">{{ __('landing.testimonial_4_author') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <p>{{ __('landing.testimonial_5_text') }}</p>
                                    <p class="author">{{ __('landing.testimonial_5_author') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('landing.carousel_prev') }}</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('landing.carousel_next') }}</span>
                </button>
            </div>
        </div>
    </section>
</div>
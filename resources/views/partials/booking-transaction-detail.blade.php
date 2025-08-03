<div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $transaction->id }}">{{ __('booking-history.modal.detail_title') }} #{{ $transaction->id }}</h5>
                
                @if(in_array($transaction->transaction_status_id, [4, 5]) && !$transaction->vehicleReview)
                    <span class="badge bg-warning text-dark ms-2">{{ __('booking-history.status.awaiting_review') }}</span>
                @elseif($transaction->transaction_status_id == 6 || $transaction->vehicleReview)
                     <span class="badge bg-success ms-2">{{ __('booking-history.status.reviewed') }}</span>
                @elseif ($transaction->transaction_status_id == 1)
                    <span class="badge bg-warning text-dark ms-2">{{ __('booking-history.status.on_payment') }}</span>
                @elseif ($transaction->transaction_status_id == 2)
                    <span class="badge bg-info ms-2">{{ __('booking-history.status.on_booking') }}</span>
                @elseif ($transaction->transaction_status_id == 3)
                    <span class="badge bg-primary ms-2">{{ __('booking-history.status.vehicle_taken') }}</span>
                @elseif ($transaction->transaction_status_id == 7)
                    <span class="badge bg-danger ms-2">{{ __('booking-history.status.canceled') }}</span>
                @endif

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($transaction->transaction_status_id == 1 && $transaction->payment?->status == 'PENDING')
                    <div class="alert alert-info text-center mb-4">
                        <h6 class="alert-heading mb-0">{{ __('payment.title.countdown') }}</h6>
                        <h2 class="font-monospace my-1" id="countdown-{{ $transaction->id }}">00:00</h2>
                    </div>
                    <hr>
                @endif
                @if ($transaction->payment->status == 'PAID')
                    <div class="alert alert-success text-center mb-4">
                        <p class="mb-0">{{ __('booking-history.modal.payment_success_message', ['date' => \Carbon\Carbon::parse($transaction->payment->paid_at)->format('d M Y H:i T')]) }}</p>
                    </div>
                @elseif($transaction->payment->status == 'CANCELED')
                    <div class="alert alert-danger text-center mb-4">
                        <p class="mb-0">{{ __('booking-history.modal.order_canceled_message', ['date' => \Carbon\Carbon::parse($transaction->payment->updated_at)->format('d M Y H:i T')]) }}</p>
                    </div>
                @elseif($transaction->payment->status == 'EXPIRED')
                    <div class="alert alert-danger text-center mb-4">
                        <p class="mb-0">{{ __('booking-history.modal.payment_expired_message', ['date' => \Carbon\Carbon::parse($transaction->payment->updated_at)->format('d M Y H:i T')]) }}</p>
                    </div>
                @endif

                <div class="text-center mb-4">
                    <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/400' }}" class="img-fluid rounded" style="max-height: 250px; width: auto;" alt="Vehicle Image">
                </div>
                
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.customer_name') }}:</strong>
                            <p class="text-muted mb-0">{{ $transaction->user?->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.vehicle') }}:</strong>
                            <p class="text-muted mb-0">{{ $transaction->vehicle?->vehicleName?->name }} ({{ $transaction->vehicle?->year }})</p>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.booking_dates') }}:</strong>
                            <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} {{ __('booking-history.modal.to') }} {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.transaction_date') }}:</strong>
                            <p class="text-muted mb-0">{{ $transaction->created_at?->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.variant') }}:</strong>
                            <p class="text-muted mb-0">{{ $transaction->vehicle?->vehicleType?->type }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('booking-history.modal.driver_name') }}:</strong>
                            <p class="text-muted mb-0">{{ $transaction->driver ? $transaction->driver->name : __('booking-history.modal.without_driver') }}</p>
                        </div>
                    </div>
                </div>
                <hr>
                
                <h5 class="text-center mb-3">{{ __('booking-history.pdf.order_details') }}</h5>
                <table class="table table-sm" style="width: 70%; margin-left: auto; margin-right: auto;">
                    <tbody>
                        <tr>
                            <td>{{ __('booking-history.modal.payment_method') }}</td>
                            <td class="text-end fw-bold">{{ $transaction->payment?->payment_channel ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="border-bottom pb-2">{{ __('booking-history.modal.payment_status') }}</td>
                            <td class="text-end fw-bold border-bottom pb-2">{{ $transaction->payment?->status }}</td>
                        </tr>

                        @php
                            $balancePrice = 50000;
                            $vehiclePrice = $transaction->price - $balancePrice;
                            $driverFee = ($transaction->driver_price > 0 || $transaction->driver_id) ? ($transaction->driver_price > 0 ? $transaction->driver_price : 50000) : 0;
                            $thisItemTotal = $vehiclePrice + $driverFee;
                        @endphp

                        <tr>
                            <td class="pt-2">{{ __('booking-history.modal.vehicle_price_label') }}</td>
                            <td class="text-end pt-2">Rp{{ number_format($vehiclePrice, 0, ',', '.') }}</td>
                        </tr>

                        @if ($driverFee > 0)
                            <tr>
                                <td>{{ __('booking-history.modal.driver_fee_label') }}</td>
                                <td class="text-end">Rp{{ number_format($driverFee, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        
                        <tr class="bg-light fw-bolder" style="border-top: 2px solid #343a40;">
                            <td class="pt-2">{{ __('booking-history.modal.total_payment_label') }}</td>
                            <td class="text-end fs-5 text-primary pt-2">Rp{{ number_format($thisItemTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                @if ($transaction->vehicleReview)
                    <hr class="my-4">
                    <div class="text-center">
                        <h5 class="mt-2">{{ __('booking-history.modal.submitted_review') }}</h5>
                        <div class="rating larger-stars text-warning" style="font-size: 5rem; direction: ltr;">
                            @for ($i = 1; $i <= 5; $i++)
                                {!! $i <= $transaction->vehicleReview->rate ? '★' : '☆' !!}
                            @endfor
                        </div>
                        <blockquote class="blockquote mt-2">
                            <p class="mb-0 fst-italic">"{{ $transaction->vehicleReview->comment }}"</p>
                        </blockquote>
                    </div>
                @elseif(in_array($transaction->transaction_status_id, [4,5]))
                    <hr class="my-4">
                    <div class="review-form-container"> 
                        <h5 class="text-center">{{ __('booking-history.modal.leave_review_title') }}</h5>
                        <form class="review-form" action="{{ route('reviews.store', $transaction) }}" method="POST">
                            @csrf
                            <div class="alert alert-danger d-none general-error mb-3"></div>
                            <div class="text-center mb-3">
                                <label class="form-label d-block text-center">{{ __('booking-history.modal.your_rating') }}</label>
                                <div class="rating larger-stars">
                                    <input type="radio" id="star5-{{$transaction->id}}" name="rating" value="5"><label for="star5-{{$transaction->id}}" title="5 stars">★</label>
                                    <input type="radio" id="star4-{{$transaction->id}}" name="rating" value="4"><label for="star4-{{$transaction->id}}" title="4 stars">★</label>
                                    <input type="radio" id="star3-{{$transaction->id}}" name="rating" value="3"><label for="star3-{{$transaction->id}}" title="3 stars">★</label>
                                    <input type="radio" id="star2-{{$transaction->id}}" name="rating" value="2"><label for="star2-{{$transaction->id}}" title="2 stars">★</label>
                                    <input type="radio" id="star1-{{$transaction->id}}" name="rating" value="1"><label for="star1-{{$transaction->id}}" title="1 star">★</label>
                                </div>
                                <div class="invalid-feedback d-block" data-field="rating"></div>
                            </div>
                            <div class="mb-3 text-center">
                                <label for="comment-{{$transaction->id}}" class="form-label text-center">{{ __('booking-history.modal.your_comment') }}</label>
                                <textarea name="comment" class="form-control" id="comment-{{$transaction->id}}" rows="3" required></textarea>
                                <div class="invalid-feedback d-block" data-field="comment"></div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">{{ __('booking-history.modal.submit_review') }}</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                @if (in_array($transaction->transaction_status_id, [1, 2]))
                    <form action="{{ route('booking.cancel', $transaction) }}" method="POST" onsubmit="return confirm('{{ __('booking-history.cancel_validation') }}');">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('booking-history.cancel_order_btn') }}</button>
                    </form>
                @endif
                @if ($transaction->payment?->status == 'PAID')
                    <a href="{{ route('receipt.download', ['payment' => $transaction->payment_id]) }}" class="btn btn-primary ...">
                        {{ __('booking-history.button.download_receipt') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('detailModal{{ $transaction->id }}');
        if (!modalElement) return;

        const countdownElement = modalElement.querySelector('#countdown-{{ $transaction->id }}');
        if (countdownElement && '{{ $transaction->transaction_status_id }}' === '1') {
            const paymentCreatedAt = new Date('{{ $transaction->payment?->created_at->toISOString() }}');
            const duration = 120 * 1000;
            const expiryTime = paymentCreatedAt.getTime() + duration;
            let timerInterval;

            const expireTransaction = () => {
                fetch(`{{ route('booking.expire', $transaction) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error expiring transaction:', error);
                });
            };

            const updateCountdown = () => {
                const now = new Date().getTime();
                const timeLeft = expiryTime - now;

                if (timeLeft > 0) {
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                    countdownElement.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    countdownElement.innerHTML = "{{ __('booking-history.modal.countdown_expired') }}";
                    clearInterval(timerInterval);
                    expireTransaction();
                }
            };

            timerInterval = setInterval(updateCountdown, 1000);
            updateCountdown();
        }

        const reviewForm = modalElement.querySelector('.review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const submitButton = reviewForm.querySelector('button[type="submit"]');
                const generalErrorDiv = reviewForm.querySelector('.general-error');

                submitButton.disabled = true;
                submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
                reviewForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                reviewForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                generalErrorDiv.classList.add('d-none');

                fetch(reviewForm.action, {
                    method: 'POST',
                    body: new FormData(reviewForm),
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
                                const errorElement = reviewForm.querySelector(`.invalid-feedback[data-field="${field}"]`);
                                const inputElement = reviewForm.querySelector(`[name="${field}"]`);
                                if (inputElement) inputElement.classList.add('is-invalid');
                                if (errorElement) errorElement.textContent = body.errors[field][0];
                            }
                        }
                    } else if (status === 200 && body.success) { 
                        alert(body.message || 'Review submitted successfully!');
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
                    submitButton.innerHTML = "{{ __('booking-history.modal.submit_review') }}";
                });
            });
        }
    });
</script>
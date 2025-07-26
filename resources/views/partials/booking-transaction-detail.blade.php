<div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $transaction->id }}">{{ __('booking-history.modal.detail_title') }} #{{ $transaction->id }}</h5>
                @if($transaction->transaction_status_id == 5 && !$transaction->vehicleReview)
                    <span class="badge bg-warning text-dark ms-2">{{ __('booking-history.status.awaiting_review') }}</span>
                @elseif($transaction->vehicleReview)
                    <span class="badge bg-success ms-2">{{ __('booking-history.modal.reviewed') }}</span>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                            <p class="text-muted mb-0">{{ $transaction->driver ? $transaction->driver->name : 'Without Driver' }}</p>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-borderless table-sm" style="width: 55%; margin-left: auto; text-align: left;">
                    <tbody>
                        <tr>
                            <td>{{ __('booking-history.modal.vehicle_price') }}</td>
                            <td class="text-end">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                        </tr>
                        @if ($transaction->driver_fee > 0)
                        <tr>
                            <td>{{ __('booking-history.modal.driver_fee') }}</td>
                            <td class="text-end">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="fw-bold" style="border-top: 1px solid #dee2e6;">
                            <td>{{ __('booking-history.label.total_price') }}</td>
                            <td class="text-end">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                @if ($transaction->vehicleReview)
                    <hr class="my-4">
                    <div class="text-center">
                        <h5 class="mt-2">{{ __('booking-history.modal.submitted_review') }}</h5>
                        <div class="rating larger-stars text-warning" style="font-size: 2.5rem; direction: ltr;">
                            @for ($i = 1; $i <= 5; $i++)
                                {!! $i <= $transaction->vehicleReview->rate ? '★' : '☆' !!}
                            @endfor
                        </div>
                        <blockquote class="blockquote mt-3">
                            <p class="mb-0 fst-italic">"{{ $transaction->vehicleReview->comment }}"</p>
                        </blockquote>
                    </div>

                @elseif(in_array($transaction->transaction_status_id, [4,5]))
                    <hr class="my-4">
                    <div class="review-form-container"> 
                        <h5 class="text-center">{{ __('booking-history.modal.leave_review_title') }}</h5>
                        @if ($errors->any())
                            <div class="alert alert-danger text-start" role="alert">
                                <ul class="mb-0">
                                    {{-- Loop untuk menampilkan semua pesan error --}}
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
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
                                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" id="comment-{{$transaction->id}}" rows="3" required>{{ old('comment') }}</textarea>
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
                <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary">{{ __('booking-history.button.download_receipt') }}</a>
            </div>
        </div>
    </div>
</div>
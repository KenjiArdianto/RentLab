<div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $transaction->id }}">Detail Transaction #{{ $transaction->id }}</h5>
                @if($transaction->status == 4)
                    <span class="badge bg-warning text-dark ms-2">Awaiting Your Review</span>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/400' }}" class="img-fluid rounded mb-4" alt="Vehicle Image">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Customer Name:</strong>
                            <p class="text-muted mb-0">{{ $transaction->user?->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Vehicle:</strong>
                            <p class="text-muted mb-0">{{ $transaction->vehicle?->vehicleName?->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Booking Dates:</strong>
                            <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Transaction Date:</strong>
                            <p class="text-muted mb-0">{{ $transaction->created_at?->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Variant:</strong>
                            <p class="text-muted mb-0">{{ $transaction->vehicle?->vehicleType?->type }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Driver Name:</strong>
                            <p class="text-muted mb-0">{{ $transaction->driver ? $transaction->driver->name : 'Without Driver' }}</p>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-borderless table-sm" style="width: 55%; margin-left: auto; text-align: left;">
                    <tbody>
                        <tr>
                            <td>Vehicle Price</td>
                            <td class="text-end">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                        </tr>
                        @if ($transaction->driver_fee > 0)
                        <tr>
                            <td>Driver Fee</td>
                            <td class="text-end">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="fw-bold" style="border-top: 1px solid #dee2e6;">
                            <td>Total Price</td>
                            <td class="text-end">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                @if($transaction->status == 4)
                    <hr class="my-4">
                    <div class="review-form-container"> 
                        <h5 class="mt-2">Leave a Review</h5>

                        <form action="{{ route('reviews.store', $transaction) }}" method="POST" class="w-100 text-center">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label d-block">Your Rating</label>
                                <div class="rating larger-stars">
                                    <input type="radio" id="star5-{{$transaction->id}}" name="rating" value="5" required><label for="star5-{{$transaction->id}}" title="5 stars">★</label>
                                    <input type="radio" id="star4-{{$transaction->id}}" name="rating" value="4"><label for="star4-{{$transaction->id}}" title="4 stars">★</label>
                                    <input type="radio" id="star3-{{$transaction->id}}" name="rating" value="3"><label for="star3-{{$transaction->id}}" title="3 stars">★</label>
                                    <input type="radio" id="star2-{{$transaction->id}}" name="rating" value="2"><label for="star2-{{$transaction->id}}" title="2 stars">★</label>
                                    <input type="radio" id="star1-{{$transaction->id}}" name="rating" value="1"><label for="star1-{{$transaction->id}}" title="1 star">★</label>
                                </div>
                                <div class="invalid-feedback d-block" data-field="rating"></div>
                            </div>
                            <div class="mb-3">
                                <label for="comment-{{$transaction->id}}" class="form-label">Your Comment</label>
                                <textarea name="comment" class="form-control" id="comment-{{$transaction->id}}" rows="3" required></textarea>
                                {{-- Tempat untuk pesan error komentar --}}
                                <div class="invalid-feedback d-block" data-field="comment"></div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit Review</button>
                        </form>
                    </div>
                @elseif ($transaction->userReview)
                    <hr class="my-4">
                    <div class="text-center">
                        <h5 class="mt-2">Your Submitted Review</h5>
                        <div class="rating larger-stars text-warning" style="font-size: 2rem;">
                            @for ($i = 1; $i <= 5; $i++)
                                {{-- Tampilkan bintang sesuai rating yang tersimpan di database --}}
                                {!! $i <= $transaction->userReview->rate ? '★' : '☆' !!}
                            @endfor
                        </div>
                        <blockquote class="blockquote mt-3">
                            <p class="mb-0 fst-italic">"{{ $transaction->userReview->comment }}"</p>
                        </blockquote>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary">Download Receipt</a>
            </div>
        </div>
    </div>
</div>
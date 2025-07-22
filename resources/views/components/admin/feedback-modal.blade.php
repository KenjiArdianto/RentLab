@if(session('success') || session('error'))
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white py-2 {{ session('success') ? 'bg-success' : 'bg-danger' }}">
                    <h6 class="modal-title d-flex align-items-center" id="feedbackModalLabel">
                        {{ session('success') ? 'Success' : 'Error' }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">{{ session('success') ? session('success') : session('error') }}</p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm {{ session('success') ? 'btn-success' : 'btn-danger' }}" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('feedbackModal');
            if (modalEl) {
                const feedbackModal = new bootstrap.Modal(modalEl);
                feedbackModal.show();

                setTimeout(() => {
                    const backdrop = document.querySelector('.modal-backdrop');
                    feedbackModal.hide();
                }, 4000);
            }
        });
    </script>
@endif
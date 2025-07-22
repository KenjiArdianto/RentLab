<x-layout>

    <div class="container">
        {{--ini nav bar--}}
        <div class="row">
            <div class="ms-1 pe-3 ps-3 pb-3 mt-3" >
                <div class="d-flex col-12 bg-light border rounded p-3 ps-0 pe-0">
                    <div class="col-1 d-flex justify-content-center align-items-center">
                    </div>
                    <div class="d-flex col-11 ps-4">
                        <div class="col-2 d-flex align-items-center">
                            <p class="fw-bold m-0">Produk</p>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-3 d-flex align-items-center">
                            <p class="fw-bold m-0">Tanggal Pesanan</p>
                        </div>
                        <div class="col-3 d-flex align-items-center">
                            <p class="fw-bold m-0">Total Harga</p>
                        </div>
                    </div>
                </div>
                {{-- isi disini --}}
            </div>
        </div>
        @foreach ($listCart as $cart)
        <x-CartCardComponent :param="$cart">
        </x-CartCardComponent>

        @endforeach

    
        {{-- ini footer --}}

        <div class="row sticky-bottom">
            <div class="ms-1 pe-3 ps-3 pb-0 mt-3" >
                    <div class="col-12">
                        
                        <div class="bg-light d-flex ms-4 align-items-center" style="height: 70px">

                            <h1 class="col-1"></h1>
                            <div class="col-6">
                                <h5 class="mb-0">Total (<span id="total-product">0</span> Produk) : </h5>
                                <h6 class="text-muted">Pembayaran dapat dilanjutkan dengan menekan Payment</h6>
                            </div>
                            <h3 class="col-3">Rp.<span id="total-price">0</span>,00</h3>
                        
                            <button type="button" class="btn btn-primary m-3" style="height: 40px; width: 60%;">
                                    <a href="" class="text-light text-decoration-none">Payment </a>
                            </button>
                        </div>
                    </div>
            </div>
        </div>

        
        
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        const totalPriceEl = document.getElementById('total-price');
        const totalProductEl = document.getElementById('total-product');

        function updateCartSummary() {
            let total = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.dataset.price);
                    count++;
                }
            });

            totalPriceEl.innerText = total.toLocaleString('id-ID');
            totalProductEl.innerText = count;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateCartSummary);
        });

        // Initial check (optional if you have some pre-checked)
        updateCartSummary();
    });
</script>

</x-layout> 

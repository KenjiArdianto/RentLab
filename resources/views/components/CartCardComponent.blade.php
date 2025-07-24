@props(['param'])

<div class="row mb-3 d-lg-block d-none cart-item-container-desktop">
    <div class="d-flex col-12">
        <div class="col-1 d-flex justify-content-center align-items-center">
            <label>
                <input type="checkbox" class="cart-checkbox desktop-checkbox" data-price="{{ $param->vehicle->price }}">
            </label>
        </div>
        <div class="d-flex col-11 bg-light p-2 ps-4 rounded-3 shadow-sm">
            <div class="col-2">
                <img class="img-fluid" src="{{ $param->vehicle->main_image }}" alt="" style="max-width: 129px; height:73px; object-fit: contain;">
            </div>
            <div class="col-4 d-flex align-items-center">
                <div>
                    <h6 class="m-0">{{ $param->vehicle->vehiclename->name }}</h6>
                    <h6 class="text-muted">{{ $param->vehicle->location->location }}</h6>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center">
                <h6 class="m-0"> {{ \Carbon\Carbon::parse($param->start_date)->format('j M Y') }} - {{ \Carbon\Carbon::parse($param->end_date)->format('j M Y') }}</h6>
            </div>

            <div class="col-2 d-flex align-items-center">
                <div>
                    <h6 class="text-muted m-0">Total Harga</h6>
                    <h5>Rp.{{ $param->vehicle->price }},00</h5>
                </div>
            </div>

            {{-- <form class="col-1 m-0 d-flex align-items-center justify-content-center" action="/CartPage/{{ $param->id }}" method="POST"> --}}
            <form class="col-1 m-0 d-flex align-items-center justify-content-center" action="{{route('cart.destroy', ['id'=>$param->id])}}" method="POST">
                @csrf
                @method('DELETE')
                <div>
                    <button type="submit" class="bg-danger p-2 rounded-1 border-0 text-light d-flex justify-content-center align-items-center" style="width: 30px; height: 30px;">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
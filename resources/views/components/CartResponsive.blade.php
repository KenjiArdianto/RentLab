@props(['param'])

<div class="row mb-3 d-lg-none d-block cart-item-container-mobile">
    <div class="d-flex col-12 me-0 cart-responsive-item">
        <div class="col-1 d-flex justify-content-center align-items-center">
            <label>
                
                <input type="checkbox"
                    class="cart-checkbox mobile-checkbox"
                    data-cart-id="{{$param->id }}"
                    data-subtotal="{{ $param->subtotal }}"
                    data-start-date="{{ $param->start_date }}"
                    data-end-date="{{ $param->end_date }}">
            </label>
        </div>
        <div class="d-flex col-11 bg-light p-2 ps-2 rounded-3 shadow-sm">
            <div class="col-4 d-flex align-items-center ms-1">
                <img class="img-fluid" src="{{ $param->vehicle->main_image }}" alt=""
                    style="max-width: 85px; object-fit: contain;">
            </div>

            <div class="col-6 d-flex align-items-center">
                <div>
                    <h6 class="m-0 fw-semibold" style="font-size:17px">{{ $param->vehicle->vehiclename->name }}</h6>
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-geo-alt-fill me-1 text-muted" style="font-size: 11px"></i>
                        <h6 class="text-muted m-0" style="font-size:14px">{{ $param->vehicle->location->location }}</h6>
                    </div>
                    <h6 style="font-size:10px; margin-bottom: 6px"> {{ \Carbon\Carbon::parse($param->start_date)->format('j M Y') }} -
                        {{ \Carbon\Carbon::parse($param->end_date)->format('j M Y') }}
                    </h6>
                    
                    <h6 class="text-muted m-0 subtotal-display">Rp.{{ number_format($param->subtotal, 0, ',', '.') }},00 /{{__('vehicle.PerDay')}}</h6>
                </div>
            </div>


            <form class="col-2 m-0 d-flex align-items-center justify-content-center" action="{{route('cart.destroy', ['id'=>$param->id])}}"
                method="POST">
                @csrf
                @method('DELETE')
                <div>
                    <button type="submit" class="bg-danger p-2 rounded-1 border-0 text-light d-flex justify-content-center align-items-center"
                        style="width: 25px; height: 25px;">
                        <i class="bi bi-trash3" style="font-size: 10px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
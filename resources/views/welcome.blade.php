<x-layout>
    <div class="container mt-5">
        <h1>Selamat Datang di Laravel dengan Bootstrap!</h1>
        <button class="btn btn-primary">Tombol Bootstrap</button>
    </div>

    @foreach ($listItem as $items)

    <a href="/DetailPage/{{$items->id}}">
        <div class="card">
            <div class="card-body">
                <h1>{{$items->id}}</h1>
            </div>
        </div>
    </a>
    

    @endforeach

</x-layout>
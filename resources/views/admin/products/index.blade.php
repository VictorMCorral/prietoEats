@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    @if(session('success'))
    <div class="alert alert-dismissible fade show mb-5" role="alert" id="flashMessage" style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(78, 205, 196, 0.25); border-radius: var(--radius-lg); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); backdrop-filter: blur(10px); padding: 1.5rem;">
        <div style="color: #15803d; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
            <span>
                <i class="bi bi-check-circle-fill me-2" style="font-size: 1.3rem;"></i>
                {{ session('success') }}
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('flashMessage');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000);
    </script>
    @endif

    <h3 class="mb-4 text-center page-title">Productos registrados</h3>

    <!-- PLATOS -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 g-4">
        @foreach ($productos as $producto)
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ asset('storage/' . $producto->image) }}" class="card-img-top img-fluid product-img-cover" alt="{{ $producto->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $producto->name }}</h5>
                    <p class="card-text text-muted flex-grow-1">Descripción corta y atractiva del producto.</p>
                    <div class="mt-3">
                        @auth
                        <form method="POST" action="{{ route('cartAdd', $productOffer->id ) }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                Agregar al carrito
                            </button>
                        </form>
                        @endauth
                        @guest
                        <small class="text-danger d-block text-center">Es necesario estar registrado para comprarlo</small>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection

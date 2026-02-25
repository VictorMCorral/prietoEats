@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    <!-- Header Section -->
    <div class="text-center mb-5 fade-in">
        <span class="badge" style="background: linear-gradient(135deg, #FF6B6B 0%, #EE5A52 100%); color: white; border: none;">
            Nuestra Selección
        </span>
        <h1 class="main-title mt-3">Menú del Día</h1>
        <p class="text-muted fs-6">Selecciona una fecha y descubre platos únicos</p>
    </div>

    <!-- Tabs de Fechas - Diseño Moderno -->
    <ul class="nav nav-tabs-premium justify-content-center mb-5" id="tabs" role="tablist">
        @foreach ($offers as $offer)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                id="tab-{{ $offer->id }}"
                data-bs-toggle="tab"
                data-bs-target="#pane-{{ $offer->id }}"
                type="button" role="tab">
                <i class="bi bi-calendar-check me-2"></i>
                <span class="fw-semibold">{{ $offer->date_delivery->format('d M') }}</span>
            </button>
        </li>
        @endforeach
    </ul>

    <!-- Grid de Productos -->
    <div class="tab-content" id="myTabContent">
        @foreach ($offers as $offer)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
            id="pane-{{ $offer->id }}"
            role="tabpanel">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($offer->productsOffer as $productOffer)
                <div class="col">
                    <div class="card h-100 product-card">
                        <!-- Imagen -->
                        <div class="img-wrapper">
                            <img src="{{ asset('storage/' . $productOffer->product->image) }}"
                                class="img-fluid"
                                alt="{{ $productOffer->product->name }}">
                        </div>

                        <!-- Contenido -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="fw-bold mb-2" style="color: #1A1A1A; font-size: 1.125rem;">
                                {{ $productOffer->product->name }}
                            </h5>

                            <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                                {{ Str::limit($productOffer->product->description ?? 'Preparado con ingredientes frescos y seleccionados', 80) }}
                            </p>

                            <!-- Footer del Card -->
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="price-tag">
                                    {{ number_format($productOffer->product->price, 2) }}
                                    <small class="fs-6">€</small>
                                </span>

                                @auth
                                <form method="POST" action="{{ route('cartAdd', $productOffer->id) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn-add-cart">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </form>
                                @endauth
                            </div>

                            @guest
                            <div class="mt-3">
                                <div class="guest-alert text-center">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    <span class="small">Inicia sesión para ordenar</span>
                                </div>
                            </div>
                            @endguest
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

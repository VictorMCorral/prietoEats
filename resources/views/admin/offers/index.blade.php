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

    <!-- Encabezado de la Página -->
    <div class="row align-items-center mb-5 page-hero">
        <div class="col-md-8">
            <h2 class="page-title mb-1">Gestión de ofertas</h2>
            <p class="text-muted m-0">Planifica los menús diarios y ofertas para tus clientes.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 nav-link">
            <a href="{{ route('admin.offers.create') }}" class="btn-create-master d-inline-flex align-items-center">
                <i class="bi bi-calendar-plus me-2 fs-5"></i> Crear Nueva Oferta
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            @forelse ($offers as $offer)
            <!-- Tarjeta de Oferta Individual -->
            <div class="offer-card">
                <!-- Cabecera de la Oferta -->
                <div class="offer-header">
                    <div class="date-badge">
                        <i class="bi bi-calendar3"></i>
                        {{ $offer->date_delivery->format('d / m / Y') }}
                    </div>

                    <!-- Acción de Eliminar -->
                    <form method="POST" action="{{ route('admin.offers.destroy', $offer->id) }}" class="m-0" onsubmit="return confirm('¿Seguro que deseas eliminar la oferta del {{ $offer->date_delivery->format('d/m/Y') }}?')">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn-delete-offer" title="Eliminar oferta">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </form>
                </div>

                <!-- Lista de Productos de la Oferta -->
                <div class="table-responsive bg-white">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="col-thumb"></th>
                                <th>Producto Incluido</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offer->productsOffer as $productOffer)
                            <tr>
                                <td>
                                    @if($productOffer->product->image)
                                        <img src="{{ asset('storage/' . $productOffer->product->image) }}" class="product-img">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cup-hot text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $productOffer->product->name }}</span>
                                </td>
                                <td>
                                    <small class="text-muted d-block desc-ellipsis">
                                        {{ $productOffer->product->description ?? 'Sin descripción disponible.' }}
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Esta oferta no tiene productos asignados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <div class="empty-state-icon mb-3">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h4 class="fw-bold mt-3">No hay ofertas programadas</h4>
                <p class="text-muted">Crea tu primera oferta para empezar a vender.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

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

    <div class="page-hero mb-5">
        <span class="badge badge-soft-primary mb-2">
            <i class="bi bi-bag-check-fill me-1"></i>
            Revisión final
        </span>
        <h1 class="cart-title m-0">Tu carrito de reserva</h1>
        <p class="text-muted mb-0">Revisa tu pedido antes de confirmar la reserva.</p>
    </div>

    @if(count($carrito) > 0)
    @php $totalGeneral = 0; @endphp

    <div class="row g-5">
        <!-- Listado de Productos -->
        <div class="col-lg-8">
            @foreach ($carrito as $offerId => $items)
            @php $offer = $offersById[$offerId] ?? null; @endphp

            <div class="delivery-group-card">
                <div class="delivery-header">
                    <i class="bi bi-truck-flatbed text-primary fs-5"></i>
                    <div class="flex-grow-1">
                        <span class="small text-muted d-block text-uppercase fw-bold">Programado para:</span>
                        <span class="fw-bold text-dark">{{ $offer->date_delivery->format('d/m/Y') }} <span class="mx-2 text-muted">|</span> {{ $offer->time_delivery }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr style="background: linear-gradient(135deg, rgba(78, 205, 196, 0.08) 0%, transparent 100%);">
                                <th class="ps-4 py-3">Producto</th>
                                <th class="text-center py-3">Cantidad</th>
                                <th class="text-end pe-4 py-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $productOfferId => $quantity)
                            @php
                                $productOffer = $productsOffersById[$productOfferId] ?? null;
                                $producto = $productOffer->product;
                                $lineTotal = $producto->price * (int) $quantity;
                                $totalGeneral += $lineTotal;
                            @endphp
                            <tr style="border-bottom: 1px solid rgba(78, 205, 196, 0.1);">
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $producto->image) }}" class="product-img-cart" style="border-radius: 8px;">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ $producto->name }}</h6>
                                            <span class="text-muted small fw-semibold">{{ number_format($producto->price, 2) }} € / ud.</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="qty-pill shadow-sm">
                                            <form method="POST" action="{{ route('cartRemoveOne', $producto->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="qty-btn" @if($quantity <= 1) disabled @endif>
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                            </form>

                                            <span class="fw-bold px-2 text-dark qty-count">{{ $quantity }}</span>

                                            <form method="POST" action="{{ route('cartAddOne', $producto->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="qty-btn">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <form method="POST" action="{{ route('cartRemove', $productOffer->id) }}" class="ms-3">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-link text-danger p-1" title="Eliminar del carrito">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-4">
                                    <span class="fw-bold text-dark fs-5">{{ number_format($lineTotal, 2) }} €</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Sidebar de Resumen -->
        <div class="col-lg-4">
            <div class="summary-card">
                <h4>
                    <i class="bi bi-file-earmark-check" style="color: var(--primary);"></i>
                    Resumen del Pedido
                </h4>

                <div class="summary-subtotal-section">
                    <div class="row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">{{ number_format($totalGeneral, 2) }} €</span>
                    </div>
                    <div class="row">
                        <span class="summary-label">Entrega</span>
                        <span class="badge badge-soft-success" style="height: fit-content;">
                            <i class="bi bi-check-circle me-1"></i>
                            Gratis
                        </span>
                    </div>
                </div>

                <div class="summary-total-section">
                    <span class="summary-total-label">Total a pagar</span>
                    <span class="summary-total-amount">{{ number_format($totalGeneral, 2) }} €</span>
                    <span class="summary-total-iva">✓ IVA incluido</span>
                </div>

                <form method="POST" action="{{ route('cartOrder') }}">
                    @csrf
                    <button type="submit" class="btn btn-confirm w-100 mb-3">
                        <i class="bi bi-check-circle me-2"></i> CONFIRMAR RESERVA
                    </button>
                </form>

                <button type="button" class="btn w-100" data-bs-toggle="collapse" data-bs-target="#moreOptions" style="background: var(--bg-app); border: 1px solid var(--border-light); color: var(--text-secondary); font-weight: 600; border-radius: var(--radius-md);">
                    <i class="bi bi-three-dots me-2"></i> Más opciones
                </button>

                <div class="collapse mt-3" id="moreOptions">
                    <form method="POST" action="{{ route('cartClear') }}" onsubmit="return confirm('¿Vaciar todo el carrito?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" style="border-color: rgba(239, 68, 68, 0.4); color: #c41c00; font-weight: 600;">
                            <i class="bi bi-trash3 me-1"></i> Vaciar carrito
                        </button>
                    </form>
                </div>

                <div style="margin-top: 2rem; padding: 1.25rem; background: linear-gradient(135deg, rgba(78, 205, 196, 0.1) 0%, transparent 100%); border-left: 3px solid var(--primary); border-radius: 6px;">
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-shield-check flex-shrink-0" style="color: var(--primary); font-size: 1.2rem;"></i>
                        <p class="small mb-0" style="color: var(--text-secondary); line-height: 1.5;"><strong style="color: var(--text-primary);">Reserva segura</strong>. Podrás ver el estado de tu pedido en tu panel personal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Estado Vacío Innovador -->
    <div class="row justify-content-center py-5 delivery-group-card">
        <div class="col-lg-6 text-center ">
            <div class="empty-cart-icon shadow-sm" style="background: linear-gradient(135deg, rgba(78, 205, 196, 0.15) 0%, rgba(255, 107, 107, 0.1) 100%); width: 120px; height: 120px; margin: 0 auto 2rem;">
                <i class="bi bi-bag-x"></i>
            </div>
            <h2 class="fw-bold text-dark">Tu carrito está esperando</h2>
            <p class="text-muted mb-4 px-lg-5 ">Parece que aún no has seleccionado ninguna de nuestras ofertas especiales para tus próximas entregas.</p>
            <a href="/" class="btn btn-confirm px-5 fw-bold text-dark" >
                Explorar Menú de Hoy
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

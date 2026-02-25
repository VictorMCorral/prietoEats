@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    <!-- Encabezado de la Página -->
    <div class="row align-items-center mb-5 page-hero">
        <div class="col-md-8">
            <h2 class="page-title mb-1">Gestión de Pedidos por Oferta</h2>
            <p class="text-muted m-0">Visualiza todos los pedidos agrupados por oferta de entrega.</p>
        </div>
    </div>

    <!-- Lista de ofertas y pedidos -->
    @forelse($dataByOffer as $data)
    <div class="row justify-content-center mb-5">
        <div class="col-lg-12">
            <!-- Encabezado de la Oferta -->
            <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; flex-wrap: wrap !important; gap: 1rem !important; padding: 1.5rem !important; background-image: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important; background-color: #ff6b6b !important; color: white !important; border-radius: 16px 16px 0 0 !important;">
                <div>
                    <h3 class="mb-1" style="color: white !important; margin: 0 !important;">OFERTA - {{ $data['offer']->date_delivery->format('d/m/Y') }}</h3>
                    <p class="mb-0" style="opacity: 0.95; color: white !important;">Entrega: {{ $data['offer']->time_delivery }}</p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.9rem; opacity: 0.9; color: white !important;">Total de clientes</div>
                    <div style="font-size: 2rem; font-weight: bold; color: white !important;">{{ count($data['users']) }}</div>
                </div>
            </div>

            <!-- Tabla de pedidos -->
            <div style="background: white; border: 1px solid rgba(78, 205, 196, 0.2); border-top: none; border-radius: 0 0 var(--radius-lg) var(--radius-lg); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow-x: auto;">
                <table class="table table-hover mb-0" style="margin: 0;">
                    <thead style="background: rgba(78, 205, 196, 0.08); border-bottom: 2px solid rgba(78, 205, 196, 0.2);">
                        <tr>
                            <th style="padding: 1rem; font-weight: 600; color: var(--text-primary); min-width: 150px;">Cliente</th>
                            @foreach($data['products'] as $productOffer)
                                <th style="padding: 1rem; font-weight: 600; color: var(--text-primary); text-align: center; min-width: 120px;">{{ $productOffer->product->name }}</th>
                            @endforeach
                            <th style="padding: 1rem; font-weight: 600; color: var(--text-primary); text-align: right; min-width: 100px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['users'] as $userId => $userData)
                        <tr style="border-bottom: 1px solid rgba(78, 205, 196, 0.1);">
                            <td style="padding: 1rem; font-weight: 500; color: var(--text-primary);">
                                <i class="bi bi-person-circle me-2" style="color: var(--primary);"></i>
                                {{ $userData['name'] }}
                            </td>
                            @foreach($data['products'] as $productOffer)
                                <td style="padding: 1rem; text-align: center; color: var(--text-primary);">
                                    @php
                                        $quantity = $userData['products'][$productOffer->product->id]['quantity'] ?? 0;
                                    @endphp
                                    @if($quantity > 0)
                                        <span style="background: rgba(78, 205, 196, 0.15); padding: 0.4rem 0.8rem; border-radius: 4px; font-weight: 600; color: var(--primary);">{{ $quantity }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td style="padding: 1rem; text-align: right; font-weight: 700; color: var(--primary); font-size: 1.1rem;">
                                {{ number_format($userData['total'], 2) }} €
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($data['products']) + 2 }}" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                                <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5; display: block; margin-bottom: 0.5rem;"></i>
                                Sin pedidos para esta oferta
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div style="text-align: center; padding: 3rem; background: rgba(78, 205, 196, 0.05); border-radius: var(--radius-lg); border: 1px dashed rgba(78, 205, 196, 0.3);">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--primary); opacity: 0.5; display: block; margin-bottom: 1rem;"></i>
                <h5 class="text-muted">No hay pedidos en el sistema</h5>
                <p class="text-muted small">Los clientes podrán realizar pedidos una vez que hayas creado ofertas activas.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<style>
    .table-hover tbody tr:hover {
        background: rgba(78, 205, 196, 0.05);
    }

    .page-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.8rem;
    }

    .section-spacing {
        padding: 2rem 0;
    }
</style>
@endsection

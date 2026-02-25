@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    <!-- Encabezado de la Página -->
    <div class="page-hero mb-5">
        <span class="badge admin-badge mb-2">
            <i class="bi bi-shield-check me-1"></i>
            Admin panel
        </span>
        <h1 class="page-title mt-3 mb-2">Configurador de ofertas</h1>
        <p class="text-muted mb-0">Crea y configura nuevas ofertas para tu menú diario.</p>
    </div>

    <!-- Formulario de Creación -->
    <div class="page-hero">
        <form method="POST" action="{{ route('admin.offers.store') }}">
            @csrf
            <div class="row g-5">
            <!-- COLUMNA IZQUIERDA: CONFIGURACIÓN -->
            <div class="col-lg-4">
                <div class="sticky-panel">
                    <div class="config-suite">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <h4 class="fw-bold m-0">Parámetros</h4>
                        </div>

                        <div class="mb-4">
                            <label class="form-dark-label">Fecha de entrega</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_delivery" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-dark-label">Ventana horaria</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="time_delivery" placeholder="Ej: 10:30-12:00" required>
                            </div>
                        </div>

                        <div class="info-panel mb-4">
                            <div class="d-flex gap-2">
                                <i class="bi bi-info-circle-fill text-info"></i>
                                <p class="small text-info mb-0 fw-semibold">Selecciona al menos un producto del inventario para habilitar la oferta.</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-publish w-100">
                            <i class="bi bi-rocket-takeoff me-2"></i> PUBLICAR OFERTA
                        </button>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: SELECCIÓN -->
            <div class="col-lg-8">
                <div class="selection-card">
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                        <h5 class="fw-bold m-0 text-dark">Inventario Disponible</h5>
                        <span class="badge admin-badge">{{ $productos->count() }} Items</span>
                    </div>
                    
                    <div class="p-4 border-bottom bg-light">
                        <label class="form-label fw-bold text-dark mb-2">
                            <i class="bi bi-search me-2"></i>Buscar productos
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border" style="border-color: var(--border-light) !important;">
                                <i class="bi bi-search" style="color: var(--primary);"></i>
                            </span>
                            <input type="text" id="searchProducts" class="form-control border" style="border-color: var(--border-light) !important; background: #ffffff; font-weight: 500; color: var(--text-primary);" placeholder="Buscar por nombre..." autocomplete="off">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="productsTable" class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Incluir</th>
                                    <th>Producto</th>
                                    <th class="text-end pe-4">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                <tr>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="dish_selected[]" value="{{ $producto->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($producto->image)
                                            <img src="{{ asset('storage/' . $producto->image) }}" class="product-thumb me-3 shadow-sm border">
                                            @else
                                            <div class="product-thumb me-3 d-flex align-items-center justify-content-center text-muted border">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <span class="fw-bold text-dark d-block product-name">{{ $producto->name }}</span>
                                                <span class="text-muted small product-desc">ID: #{{ $producto->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="price-badge-fresh">
                                            {{ number_format($producto->price, 2) }} €
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($productos->isEmpty())
                    <div class="text-center py-5">
                        <div class="empty-state-icon mb-3">
                            <i class="bi bi-archive"></i>
                        </div>
                        <h5 class="mt-3 text-muted">No hay productos disponibles</h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary-app px-4">Ir a inventario</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const input = document.getElementById('searchProducts');
        const table = document.getElementById('productsTable');
        if (!input || !table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Guardamos el índice original de cada fila para orden estable dentro de cada grupo
        rows.forEach((tr, idx) => tr.dataset.initialIndex = idx);

        const normalize = s =>
            (s || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

        let timer;
        const debounced = (fn, delay = 120) => (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };

        const applyFilterAndSort = () => {
            const q = normalize(input.value.trim());

            // 1) Calcular visibilidad y separar en dos grupos
            const selected = [];
            const unselected = [];

            rows.forEach(tr => {
                const checkbox = tr.querySelector('input[type="checkbox"]');
                const isChecked = checkbox && checkbox.checked;

                const name = normalize(tr.querySelector('.product-name')?.textContent);
                const desc = normalize(tr.querySelector('.product-desc')?.textContent);
                const matchesQuery = !q || name.includes(q) || desc.includes(q);

                // Si está marcado, SIEMPRE visible; si no, depende del filtro
                const show = isChecked || matchesQuery;
                tr.style.display = show ? '' : 'none';

                // Solo consideramos para ordenar las que están visibles
                if (show) {
                    (isChecked ? selected : unselected).push(tr);
                }
            });

            // 2) Reordenar: primero seleccionados, luego no seleccionados
            //    Manteniendo orden original dentro de cada grupo (por data-initial-index)
            selected.sort((a, b) => a.dataset.initialIndex - b.dataset.initialIndex);
            unselected.sort((a, b) => a.dataset.initialIndex - b.dataset.initialIndex);

            // 3) Reconstruir el tbody con las visibles ordenadas (las ocultas se quedan en su sitio, pero invisibles)
            const fragment = document.createDocumentFragment();
            selected.forEach(tr => fragment.appendChild(tr));
            unselected.forEach(tr => fragment.appendChild(tr));

            // Nota: las filas ocultas NO se reinsertan; permanecen en el DOM con display:none,
            // por lo que no afectan al orden visible. Si quieres mover también las ocultas al final,
            // descomenta el bloque siguiente:

            // rows.forEach(tr => {
            //     if (tr.style.display === 'none') fragment.appendChild(tr);
            // });

            tbody.appendChild(fragment);
        };

        // Filtrar y reordenar al teclear
        input.addEventListener('input', debounced(applyFilterAndSort, 120));

        // Reaplicar cuando cambie cualquier checkbox (selección/deselección)
        table.addEventListener('change', (e) => {
            if (e.target.matches('input[type="checkbox"]')) applyFilterAndSort();
        });

        // Primera pasada
        applyFilterAndSort();
    })();
</script>
@endsection

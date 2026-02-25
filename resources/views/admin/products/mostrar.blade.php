@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    <div class="admin-hero mb-4">
        <h2 class="page-title">Panel de gestión</h2>
        <p class="text-muted mb-0">Control total sobre tu inventario de productos.</p>
    </div>

    <div class="row g-4">
        <!-- Tabla de Productos -->
        <div class="col-lg-8">
            <div class="table-container shadow-sm border">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0 fw-bold text-secondary">Productos Registrados</h5>
                    <span class="badge admin-badge">
                        {{ $productos->count() }} Unidades
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Vista</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                            <tr>
                                <td>
                                    @if($producto->image)
                                        <img src="{{ asset('storage/' . $producto->image) }}" class="product-img">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-card-image text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $producto->name }}</div>
                                    <small class="text-muted">{{ Str::limit($producto->description, 35) }}</small>
                                </td>
                                <td>
                                    <span class="price-badge">{{ number_format($producto->price, 2) }}€</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Botón Editar con Lápiz -->
                                        <a href="{{ route('admin.products.edit', $producto->id) }}"
                                           class="action-btn btn-edit"
                                           title="Editar producto">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <!-- Botón Eliminar con Papelera -->
                                        <form method="POST" action="{{ route('admin.products.destroy', $producto->id) }}"
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="action-btn btn-delete" title="Eliminar producto">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulario de Creación -->
        <div class="col-lg-4">
            <div class="card shadow-sm border">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-box-seam-fill text-primary fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Nuevo Producto</h5>
                    </div>

                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre del Producto</label>
                            <input type="text" class="form-control" name="name" placeholder="Ej: Monitor UltraWide" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Precio de Venta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">€</span>
                                <input type="number" step="0.01" class="form-control border-start-0" name="price" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Descripción Corta</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Detalles clave..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Imagen Representativa</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-create w-100 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i> Crear Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends("layouts_prieto.home")

@section("content")
<div class="container section-spacing">
    <!-- Botón Volver -->
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Volver al listado
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="edit-card p-4 p-md-5">
                <div class="text-center mb-5">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-pencil-square text-warning fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Editar Producto</h3>
                    <p class="text-muted small">Modifica los detalles de <strong>{{ $producto->name }}</strong></p>
                </div>

                <form method="POST" action="{{ route('admin.products.update', $producto->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Nombre del producto</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name', $producto->name) }}" placeholder="Ej: Monitor Pro" required>
                    </div>

                    <!-- Precio -->
                    <div class="mb-4">
                        <label for="price" class="form-label">Precio de venta (€)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted input-group-euro">€</span>
                            <input type="number" step="0.01" class="form-control border-start-0" id="price" name="price"
                                   value="{{ old('price', $producto->price) }}" placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div class="mb-5">
                        <label for="image" class="form-label">Actualizar Imagen</label>
                        <input type="file" class="form-control mb-3" id="image" name="image" accept="image/*">

                        @if($producto->image)
                            <div class="mt-3">
                                <p class="small text-muted fw-bold mb-2">Imagen actual en sistema:</p>
                                <div class="current-image-wrapper">
                                    <img src="{{ asset('storage/' . $producto->image) }}" alt="Previsualización" class="img-preview">
                                </div>
                            </div>
                        @else
                            <div class="p-3 border rounded text-center bg-light">
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Este producto no tiene imagen asignada.</small>
                            </div>
                        @endif
                    </div>

                    <!-- Botón de acción -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-update">
                            <i class="bi bi-check2-circle me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center mt-4 text-muted small">
                ID del Producto: <span class="fw-bold">#{{ $producto->id }}</span> |
                Última actualización: {{ $producto->updated_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</div>
@endsection

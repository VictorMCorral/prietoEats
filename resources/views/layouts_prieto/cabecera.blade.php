<header class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid px-lg-4">
        <a href="{{ route('home_prieto') }}" class="navbar-brand d-flex align-items-center me-0">
            <img src="{{ asset('storage/img/logoN.png') }}" alt="Logo" class="logo-img me-2">
            <span class="brand-text">Prieto Eats</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="#menu" class="nav-link nav-link-custom">
                        <i class="bi bi-grid me-1"></i> Menú
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link nav-link-custom">
                        <i class="bi bi-info-circle me-1"></i> Info
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 auth-buttons-mobile">
                @guest
                    <a href="{{ route('login_prieto') }}" class="btn btn-auth btn-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                    </a>
                    <a href="{{ route('register_prieto') }}" class="btn btn-auth btn-register">
                        <i class="bi bi-person-plus me-1"></i> Registrarse
                    </a>
                @endguest

                @auth
                    <a href="{{ route('cartShow') }}" class="btn btn-cart d-flex align-items-center gap-2 position-relative">
                        <i class="bi bi-bag fs-5"></i>
                        <span class="d-none d-lg-inline fw-semibold">Carrito</span>
                        @php
                            $carrito = session('carrito', []);
                            $totalProductos = 0;
                            foreach ($carrito as $offerId => $items) {
                                foreach ($items as $productOfferId => $cantidad) {
                                    $totalProductos += $cantidad;
                                }
                            }
                        @endphp
                        @if($totalProductos > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-cart">
                                {{ $totalProductos }}
                                <span class="visually-hidden">productos</span>
                            </span>
                        @endif
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-auth btn-register dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="d-none d-lg-inline">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('ordersShow') }}">
                                    <i class="bi bi-clock-history"></i> Mis Pedidos
                                </a>
                            </li>

                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li class="px-3 py-1">
                                    <small class="text-muted text-uppercase admin-label">Admin</small>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.index') }}">
                                        <i class="bi bi-receipt"></i> Pedidos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.offers.index') }}">
                                        <i class="bi bi-tag"></i> Ofertas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.products.index') }}">
                                        <i class="bi bi-box-seam"></i> Productos
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout_prieto') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Salir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

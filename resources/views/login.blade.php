@extends("layouts_prieto.home")

@section("content")
<div class="auth-page">
    <div class="auth-card text-center">
        <a href="{{ route('home_prieto') }}">
            <img src="{{ asset('storage/img/logoN.png') }}" alt="Logo" class="auth-logo">
        </a>

        <h2 class="auth-title">Bienvenido</h2>
        <p class="auth-subtitle mb-4">Inicia sesión para continuar en Prieto Eats</p>

        @if($errors->any())
        <div class="alert-auth text-start mb-4">
            <ul class="mb-0 list-unstyled">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('login_prieto') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="email" id="floatingInput" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="floatingInput">Correo electrónico</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" id="floatingPassword" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password" required>
                <label for="floatingPassword">Contraseña</label>
            </div>

            <button class="btn-auth-submit" type="submit">
                Entrar ahora <i class="bi bi-arrow-right-short ms-1"></i>
            </button>
        </form>

        <div class="mt-4 pt-2">
            <p class="small text-muted mb-0">¿No tienes cuenta?</p>
            <a href="{{ route('register_prieto') }}" class="auth-link">Regístrate aquí</a>
        </div>
    </div>
</div>
@endsection

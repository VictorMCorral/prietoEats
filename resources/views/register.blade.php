@extends("layouts_prieto.home")

@section("content")
<div class="auth-page">
    <div class="auth-card text-center">
        <div class="mb-3">
            <img src="{{ asset('storage/img/logoN.png') }}" alt="Logo" class="auth-logo">
        </div>

        <h2 class="auth-title">Crea tu cuenta</h2>
        <p class="auth-subtitle mb-4">Únete a la comunidad de Prieto Eats</p>

        @if($errors->any())
        <div class="alert-auth text-start mb-4">
            <ul class="mb-0 list-unstyled">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register_prieto') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    id="regName" placeholder="Tu nombre" value="{{ old('name') }}" required>
                <label for="regName">Nombre completo</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="regEmail" placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="regEmail">Correo electrónico</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="regPassword" placeholder="Contraseña" required>
                <label for="regPassword">Contraseña</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" name="password_confirmation"
                    class="form-control"
                    id="regPasswordConfirm" placeholder="Confirmar contraseña" required>
                <label for="regPasswordConfirm">Repetir contraseña</label>
            </div>

            <button class="btn-auth-submit" type="submit">
                Registrarse ahora <i class="bi bi-arrow-right-short ms-1"></i>
            </button>
        </form>

        <div class="mt-4 pt-2">
            <p class="small text-muted mb-0">¿Ya tienes una cuenta?</p>
            <a href="{{ route('login_prieto') }}" class="auth-link">Inicia sesión aquí</a>
        </div>
    </div>
</div>
@endsection

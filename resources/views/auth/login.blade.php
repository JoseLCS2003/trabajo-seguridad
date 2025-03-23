<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Iniciar sesión</h5>
			<h5 class="card-title">Servidor 1</h5>
                        <!-- Mensajes de estado -->
                        @if (session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }}
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Formulario de login -->
                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <!-- Correo -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" >
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}"></div>

                            <!-- Botón de login -->
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>

                        <div class="mt-3 text-center">
                            <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

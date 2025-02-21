<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar tu código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Ingresa tu código de verificación</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Formulario con el parámetro id -->
                        <form action="{{ route('verify.code', ['id' => $id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="code">Código de verificación</label>
                                <input type="text" id="code" name="code" class="form-control">
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha mt-2" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                            <button type="submit" class="btn btn-primary mt-3">Verificar código</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
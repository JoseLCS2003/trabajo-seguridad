<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio - Laravel</title>
    
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="text-center">
                <h1>¡Hola, {{ auth()->user()->name }}!</h1>
            </div>            
            <div class="card-body">
                <p>Bienvenido a la página de inicio.</p>
                
                <!-- Botón de cerrar sesión -->
                <div class="mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y Popper.js desde CDN -->
    <script 
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
        integrity="sha384-QF2aYAWcZ9HSTplbtYBXjLBlax5l9Bo/yTAAs/HK3KflAsBrhPNo+0uuawRk/Eu1" 
        crossorigin="anonymous"
    ></script>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" 
        integrity="sha384-OERcA2KkVU6PQQ0PzM2Aw4KRb5fXn9877hBh2NS3GcUqz1hbGr2Kk3QmoK7ZeZDyx" 
        crossorigin="anonymous"
    ></script>
</body>

</html>

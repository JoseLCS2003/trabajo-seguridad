<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-top: 50px;
        }

        h1 {
            font-size: 2rem;
            color: #00796b;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2.5rem;
            color: #00796b;
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }

        p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .highlight {
            font-weight: bold;
            color: #00796b;
        }

        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 30px;
        }

        .footer a {
            color: #00796b;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Código de verificación</h1>
        <p>Hola, {{ $name }}</p>
        <p>Estás recibiendo este correo porque solicitaste un código de verificación para completar tu proceso.</p>

        <p><span class="highlight">Tu código de verificación es:</span></p>
        <h2>{{ $verificationCode }}</h2>

        <p>Por favor, ingresa este código en la aplicación para continuar con la verificación.</p>
        <p>Este código es válido por 10 minutos, por lo que asegúrate de usarlo dentro del plazo.</p>

        <p>Si no solicitaste este código, puedes ignorar este correo.</p>

        <p>Gracias por usar nuestra aplicación.</p>
    </div>

    <!-- Bootstrap 5 JS y Popper para dropdowns, modals, tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
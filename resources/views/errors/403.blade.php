<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>403 - Acceso Denegado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />
    <style>
        .error-number {
            font-size: 5rem;
            font-weight: bold;
            color: #206bc4;
        }
    </style>
</head>

<body class="d-flex flex-column">

    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">

                <div class="error-number">
                    403
                </div>
                <h2 class="h3 mt-3">Acceso Denegado</h2>
                <p class="text-muted">No tienes permisos para acceder a esta página.</p>
                <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l14 0" />
                        <path d="M5 12l4 4" />
                        <path d="M5 12l4 -4" />
                    </svg> Regresar
                </a>
            </div>
        </div>
    </div>


</body>

</html>

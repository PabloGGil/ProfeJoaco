<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link rel="stylesheet" href="../css/estilos.css"> </link>
    <style>
        .container_error {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }       
    </style>
</head>
<body>
    <div class="container_error">
        <h1>⚠️ Ocurrió un error</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errorCode)): ?>
            <p><strong>Código de error:</strong> <?= htmlspecialchars($errorCode) ?></p>
        <?php endif; ?>

        <p>Por favor, intenta nuevamente o vuelve al <a href="/">inicio</a>.</p>
    </div>
</body>
</html>

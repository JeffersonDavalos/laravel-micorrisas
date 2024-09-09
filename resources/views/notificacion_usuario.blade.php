<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Registro</title>
</head>
<body>
    <h1>¡Hola {{ $usuario['nombre'] }}!</h1>
    <p>Gracias por registrarte. Tu usuario es {{ $usuario['usuario'] }}.</p>
    <p>Saludos,</p>
    <p>El equipo de {{ config('app.name') }}</p>
</body>
</html>

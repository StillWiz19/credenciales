<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Credenciales</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="formulario">
        <h2>Registrar Nuevas Credenciales</h2>
        <form action="procesar_registrar.php" method="POST">
            <label for="nombre">Nombres: </label>
            <input type="text" id="nombres" name="nombre" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required>

            <label for="rut">Rut: </label>
            <input type="text" id="rut" name="rut" required>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" required>
            
            <label for="departamento">Departamento:</label>
            <input type="text" id="departamento" name="departamento" required>

            <label for="foto">Foto:</label>
            <input type="file" accept="image/*" capture="camera" id="foto" name="foto" required>
            
            <input type="submit" value="Registrar">
        </form>
    </div>
</body>
</html>
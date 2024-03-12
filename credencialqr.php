<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial QR</title>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $rut = $_POST["rut"];
    $cargo = $_POST["cargo"];
    $departamento = $_POST["departamento"];

    echo "<h1>Datos de la Credencial</h1>";
    echo "<p>Nombre: $nombre</p>";
    echo "<p>RUT: $rut</p>";
    echo "<p>Cargo: $cargo</p>";
    echo "<p>Departamento: $departamento</p>";

} else {
    echo "<p>No se han recibido datos del formulario.</p>";
}
?>


</body>
</html>


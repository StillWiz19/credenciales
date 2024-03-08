<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input")); // Obtener los datos enviados desde el cliente

    $nombre = $data->nombre;
    $rut = $data->rut;
    $cargo = $data->cargo;
    $departamento = $data->departamento;
    $imagenBase64 = $data->imagen;

    // Decodificar la imagen base64 y guardarla en un archivo
    $imagenData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));
    $imagenFileName = uniqid() . '.jpg'; // Generar un nombre de archivo único
    $uploadDir = 'img/';
    $imagenPath = $uploadDir . $imagenFileName;

    if (file_put_contents($imagenPath, $imagenData)) {
        echo "La imagen se ha guardado correctamente.";
    } else {
        echo "Error al guardar la imagen.";
    }
}
?>

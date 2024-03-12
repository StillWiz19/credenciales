<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Credenciales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #2f5596;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
        .eliminar-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .eliminar-btn:hover {
            background-color: #c82333;
        }

        .eliminar-icon {
            font-size: 18px;
            margin-right: 5px;
        }

        .qr-img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Listado de Credenciales</h1>

   
        <tbody>
        <?php
$user = "root";
$pass = "";
$server = "localhost";

$conexion = mysqli_connect($server, $user, $pass);

if ($conexion->connect_errno) {
    die("Conexion Fallida: " . $conexion->connect_errno);
} else {
    
}

$datab = "credenciales";
$db = mysqli_select_db($conexion, $datab);

if (!$db) {
    echo "No se ha podido encontrar la tabla";
} else {
    echo "<h3>Listado de Credenciales:</h3>";
}

if (isset($_POST["nombre"], $_POST["rut"], $_POST["cargo"], $_POST["departamento"], $_POST["fotoTemp"], $_POST["rutaQR"])) {
    $nombre = $_POST["nombre"];
    $rut = $_POST["rut"];
    $cargo = $_POST["cargo"];
    $departamento = $_POST["departamento"];
    $fotoTemp = $_POST["fotoTemp"];
    $rutaQR = $_POST["rutaQR"]; 

    
    $directorioDestino = 'img/';
    $nombreArchivo = "$rut"."$departamento" . '.jpg'; 
    $rutaDestino = $directorioDestino . $nombreArchivo;
    file_put_contents($rutaDestino, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoTemp)));

    

    
    $instruccion_SQL = "INSERT INTO registrarcredencial (nombre, rut, cargo, departamento, foto, codigoQR)
                    VALUES ('$nombre','$rut','$cargo','$departamento','$rutaDestino','$rutaQR')";

    $resultado = mysqli_query($conexion, $instruccion_SQL);
    if (!$resultado) {
        echo "Error al insertar datos: " . mysqli_error($conexion);
    }
} else {
    echo "Datos del formulario no recibidos";
}

$consulta = "SELECT * FROM registrarcredencial";
$result = mysqli_query($conexion, $consulta);

if (!$result) {
    echo "No se ha podido realizar la consulta";
}

echo "<table>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Nombre</th>";
echo "<th>Rut</th>";
echo "<th>Cargo</th>";
echo "<th>Departamento</th>";
echo "<th>Foto</th>";
echo "<th>Código QR</th>";
echo "</tr>";

while ($colum = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $colum['id']. "</td>";
    echo "<td>" . $colum['nombre']. "</td>";
    echo "<td>" . $colum['rut']. "</td>";
    echo "<td>" . $colum['cargo']. "</td>";
    echo "<td>" . $colum['departamento']. "</td>";
    echo "<td><img src='" . $colum['foto']. "' style='max-width: 100px;'></td>";
    echo "<td style='width: 150px;'><img src='" . $colum['codigoQR']. "' class='qr-img'></td>";
    echo "<td><button class='eliminar-btn' data-id='" . $colum['id'] . "'><i class='fas fa-trash-alt eliminar-icon'></i> Eliminar</button></td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conexion);
?>

        </tbody>


    <a href="index.php">Volver Atrás</a>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('eliminar-btn')) {
                var id = event.target.getAttribute('data-id');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'eliminar.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        event.target.parentNode.parentNode.remove();
                    }
                };
                xhr.send('id=' + id);
            }
        });
    </script>
</body>
</html>



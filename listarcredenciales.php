<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Credenciales</title>
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
    </style>
</head>
<body>
    <h1>Listado de Credenciales</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rut</th>
                <th>Cargo</th>
                <th>Departamento</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user = "root";
            $pass = "";
            $server = "localhost";

            $conexion = mysqli_connect($server, $user, $pass);
            $nombre = $_POST["nombre"];
            $rut = $_POST["rut"];
            $cargo = $_POST["cargo"];
            $departamento = $_POST["departamento"];
            $foto = $_POST["foto"];

            if ($conexion->connect_errno) {
                die("Conexion Fallida" . $conexion->connect_errno);
            } else {
                echo "conectado";
            }

            $datab = "credenciales";
            $db = mysqli_select_db($conexion, $datab);

            if (!$db) {
                echo "No se ha podido encontrar la tabla";
            } else {
                echo "<h3>Listado de Credenciales</h3>";
            }

            $instruccion_SQL = "INSERT INTO registrarcredencial (nombre, rut, cargo, departamento, foto)
                                VALUES ('$nombre','$rut','$cargo','$departamento','$foto')";

            $resultado = mysqli_query($conexion, $instruccion_SQL);
            $consulta = "SELECT * FROM registrarcredencial";
            $result = mysqli_query($conexion, $consulta);
            if (!$result) {
                echo "No se ha podido realizar la consulta";
            }

            while ($colum = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $colum['id']. "</td>";
                echo "<td>" . $colum['nombre']. "</td>";
                echo "<td>" . $colum['rut']. "</td>";
                echo "<td>" . $colum['cargo']. "</td>";
                echo "<td>" . $colum['departamento']. "</td>";
                echo "<td>" . $colum['foto']. "</td>";
                echo "</tr>";
            }
            mysqli_close($conexion);
            ?>
        </tbody>
    </table>

    <a href="index.php">Volver Atrás</a>
</body>
</html>



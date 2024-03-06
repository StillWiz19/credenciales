<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Credenciales</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    include("C:/xampp/htdocs/conexion/conexion.php");

    $sql = "SELECT * FROM credenciales";
    $sentencia = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($sentencia) > 0 ){
        echo "
        <div>
            <h2>Listado de Credenciales</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Cargo</th>
                    <th>Departamento</th>
                    <th>Foto</th>
                    <th>Estado</th>
                </tr>";
        while($fila = mysqli_fetch_assoc($sentencia)){
            echo "
                <tr>
                    <td>".$fila['nombre']."</td>
                    <td>".$fila['rut']."</td>
                    <td>".$fila['cargo']."</td>
                    <td>".$fila['departamento']."</td>
                    <td>".$fila['foto']."</td>
                    <td>".$fila['estado']."</td>
                </tr>";
        }
        echo "
            </table>
        </div>";
    } else {
        echo "No existen registros";
    }
    ?>
</body>
</html>

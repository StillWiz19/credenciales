<?php
if (isset($_POST['id'])) {
    $user = "root";
    $pass = "";
    $server = "localhost";
    $database = "credenciales";

    $conexion = mysqli_connect($server, $user, $pass, $database);

    if (!$conexion) {
        die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    $consulta = "DELETE FROM registrarcredencial WHERE id = '$id'";

    if (mysqli_query($conexion, $consulta)) {
        echo "Registro eliminado exitosamente";
    } else {
        echo "Error al eliminar el registro: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
} else {
    echo "No se ha proporcionado un ID para eliminar";
}
?>

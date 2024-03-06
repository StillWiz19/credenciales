<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Credenciales</title>
<link rel="stylesheet" href="styles.css">
<style>
    #vistaPreviaCredencial {
        border: 1px solid #ccc;
        padding: 20px;
        margin-top: 20px;
        width: 5.5cm;
        height: 8.5cm;
    }

    #vistaPreviaCredencial img {
        max-width: 100%;
        max-height: 100%;
        width: 3cm;
        height: 4cm;
    }
</style>
</head>
<body>
<div class="formulario">
    <h2>Registrar Nuevas Credenciales</h2>
    <form id="formulario" method="POST">
        <label for="nombre">Nombre Completo: </label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="rut">Rut: </label>
        <input type="text" id="rut" name="rut" required>

        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" required>
        
        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento" required>

        <label for="foto">Foto:</label>
        <button id="startCameraButton" type="button">Abrir Cámara</button>
        <video id="video" width="400" height="300" style="display: none;"></video>
        <button id="captureButton" style="display: none;" type="button">Capturar Foto</button>
        <canvas id="canvas" width="1650" height="2550" style="display: none;"></canvas>
        <img id="fotoMostrada" src="#" alt="Tu foto" style="display: none; max-width: 100px;">
        <input type="button" id="btnVistaPrevia" value="Vista Previa">
        
        <input type="submit" value="Registrar" name="registrar">
    </form>
</div>
<?php
include("C:/xampp/htdocs/conexion/conexion.php");

if (isset($_POST["registrar"])) {
    $nombre = $_POST["nombre"];
    $rut = $_POST["rut"];
    $cargo = $_POST["cargo"];
    $departamento = $_POST["departamento"];
    //$foto = $_POST["foto"];

    $query = "INSERT INTO credenciales (nombre,rut,cargo,departamento) VALUES (?,?,?,?)";
    $sentencia = mysqli_prepare($conexion, $query);

    mysqli_stmt_bind_param($sentencia,"ssss",$nombre,$rut,$cargo,$departamento);
    mysqli_stmt_execute($sentencia);
    $respuesta = mysqli_stmt_affected_rows($sentencia);

    if ($respuesta > 0) {
        echo "<script>alert('Credencial registrada')</script>";
    } else {
        echo "Sin registros";
    }
    mysqli_stmt_close($sentencia);
    mysqli_close($conexion);
}
?>
<script>
    const startCameraButton = document.getElementById('startCameraButton');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const fotoMostrada = document.getElementById('fotoMostrada');
    let mediaStream = null;
    let isCameraOn = false;

    startCameraButton.addEventListener('click', () => {
        if (!isCameraOn) {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                mediaStream = stream;
                video.srcObject = mediaStream;
                video.play();
                startCameraButton.textContent = 'Cerrar Cámara';
                captureButton.style.display = 'block';
                video.style.display = 'block';
                isCameraOn = true;
            })
            .catch(error => {
                console.error('Error al acceder a la cámara.', error);
            });
        } else {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => {
                    track.stop();
                });
            }
            video.srcObject = null;
            startCameraButton.textContent = 'Abrir Cámara';
            captureButton.style.display = 'none';
            video.style.display = 'none';
            isCameraOn = false;
        }
    });

    captureButton.addEventListener('click', () => {
        const fotoWidth = 260; 
        const fotoHeight = 320; 
        canvas.width = fotoWidth;
        canvas.height = fotoHeight;

        const x = (fotoWidth - video.videoWidth) / 2;
        const y = (fotoHeight - video.videoHeight) / 2;

        canvas.getContext('2d').drawImage(video, x, y, video.videoWidth, video.videoHeight);
        const fotoURL = canvas.toDataURL('image/jpeg');
        fotoMostrada.src = fotoURL;
        fotoMostrada.style.display = 'block';
    });

    document.getElementById('btnVistaPrevia').addEventListener('click', () => {
        const credencialNombre = document.getElementById('nombre').value;
        const credencialRut = document.getElementById('rut').value;
        const credencialCargo = document.getElementById('cargo').value;
        const credencialDepartamento = document.getElementById('departamento').value;
        
        const vistaPreviaCredencial = `
            <div id="vistaPreviaCredencial">
                <h3>Vista Previa de Credencial</h3>
                <img id="credencialFoto" src="${fotoMostrada.src}" alt="Vista previa de la credencial">
                <p>Nombre: ${credencialNombre}</p>
                <p>Rut: ${credencialRut}</p>
                <p>Cargo: ${credencialCargo}</p>
                <p>Departamento: ${credencialDepartamento}</p>
            </div>
        `;

        const nuevaVentana = window.open('', 'Vista Previa de Credencial', 'width=500,height=400');
        nuevaVentana.document.write('<html><head><title>Vista Previa de Credencial</title></head><body>');
        nuevaVentana.document.write(vistaPreviaCredencial);
        nuevaVentana.document.write('</body></html>');
    });
</script>
</body>
</html>





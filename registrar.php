<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Credenciales</title>
<link rel="stylesheet" href="styles.css">
<style>
    #vistaPreviaCredencial {
        display: none;
        border: 1px solid #ccc;
        padding: 20px;
        margin-top: 20px;
        width: 400px;
    }

    #vistaPreviaCredencial img {
        max-width: 100%;
        max-height: 200px;
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
        <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>
        <img id="fotoMostrada" src="#" alt="Tu foto" style="display: none; max-width: 100px;">

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>

        <input type="button" id="btnVistaPrevia" value="Vista Previa">
        
        <input type="submit" value="Registrar">
    </form>
</div>

<div id="vistaPreviaCredencial">
    <h3>Credencial</h3>
    <img id="credencialFoto" src="#" alt="Vista previa de la credencial">
    <p>Nombre: <span id="credencialNombre"></span></p>
    <p>Rut: <span id="credencialRut"></span></p>
    <p>Cargo: <span id="credencialCargo"></span></p>
    <p>Departamento: <span id="credencialDepartamento"></span></p>
</div>

<script>
    const startCameraButton = document.getElementById('startCameraButton');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const fotoMostrada = document.getElementById('fotoMostrada');
    const vistaPreviaCredencial = document.getElementById('vistaPreviaCredencial');
    const credencialFoto = document.getElementById('credencialFoto');
    const credencialNombre = document.getElementById('credencialNombre');
    const credencialRut = document.getElementById('credencialRut');
    const credencialCargo = document.getElementById('credencialCargo');
    const credencialDepartamento = document.getElementById('credencialDepartamento');
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
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const fotoURL = canvas.toDataURL('image/jpeg');
        fotoMostrada.src = fotoURL;
        fotoMostrada.style.display = 'block';
        credencialFoto.src = fotoURL;
    });

    document.getElementById('btnVistaPrevia').addEventListener('click', () => {
        credencialNombre.textContent = document.getElementById('nombre').value;
        credencialRut.textContent = document.getElementById('rut').value;
        credencialCargo.textContent = document.getElementById('cargo').value;
        credencialDepartamento.textContent = document.getElementById('departamento').value;
        vistaPreviaCredencial.style.display = 'block';
    });
</script>
</body>
</html>


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
    <form id="formulario" action="listarcredenciales.php" method="POST">
        <label for="nombre">Nombre Completo: </label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="rut">Rut: </label>
        <input type="text" id="rut" name="rut" required>

        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" required>
        
        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento" required>

        <label for="foto">Foto:</label>
        <button id="startCameraButton">Abrir Cámara</button>
        <video id="video" width="400" height="300" style="display: none;"></video>
        <button id="captureButton" style="display: none;">Capturar Foto</button>
        <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>
        <img id="fotoMostrada" src="#" alt="Tu foto" style="display: none; max-width: 100px;">

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
        
        <input type="submit" value="Registrar">
    </form>
</div>

<script>
    const startCameraButton = document.getElementById('startCameraButton');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const fotoMostrada = document.getElementById('fotoMostrada');
    let mediaStream = null;

    startCameraButton.addEventListener('click', () => {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.play();
            startCameraButton.style.display = 'none';
            captureButton.style.display = 'block';
            video.style.display = 'block';
            mediaStream = stream;
        })
        .catch(error => {
            console.error('Error al acceder a la cámara.', error);
        });
    });

    captureButton.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        fotoMostrada.src = canvas.toDataURL('image/jpeg');
        fotoMostrada.style.display = 'block';
        video.style.display = 'block'; 
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => {
                track.stop();
            });
        }
    });
</script>
</body>
</html>




<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Credenciales</title>
<link rel="stylesheet" href="styles.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<style>
   
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.6); 
    }

    .modal-content {
        margin: auto; 
        padding: 20px;
        border-radius: 10px;
        width: 200px; 
        height: 340px; 
        text-align: center;
        background-color: transparent;
        background-position: center;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #vistaPreviaCredencial {
        background-image: url('img/fondo.jpeg');
        background-size: contain;
        background-repeat: no-repeat; 
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        width: 100%; 
        height: 100%; 
        padding: 20px;
        border-radius: 10px;
        background-color: rgba(255, 255, 255, 0.05); 
    }

    #vistaPreviaCredencial h3{
        margin-top: 0;
        margin-bottom: 10;
        white-space: nowrap;
        color: white;
    }

    #vistaPreviaCredencial img {        
        width: 97px;
        height: 140px;
        margin-bottom: 10px;
        margin-top: -10px; 
    }

    #vistaPreviaCredencial p {
        margin: 0;
        color: black; 
    }



    #camaraModal {
        display: none;
    }

    #videoModal {
        width: 600px;
        height: 500px;
    }

    #camara {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    } 

</style>
</head>
<body>
<div class="formulario">
    <h2>Registrar Nuevas Credenciales</h2>
    <form action="listarcredenciales.php" name="" method="POST">
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
        <input type="hidden" id="rutaFoto" name="rutaFoto">
        <input type="hidden" id="fotoTemp" name="fotoTemp">
        <input type="hidden" id="rutaQR" name="rutaQR">
        <input type="button" id="btnGenerarQR" value="Generar QR">
        <input type="button" id="btnVistaPrevia" value="Vista Previa">
        
        <input type="submit" value="Registrar">
    </form>
</div>

<div id="codigoQR"></div>

<div id="camaraModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="camara">
        <video id="videoModal" autoplay></video>
        <button id="captureButtonModal" type="button">Capturar Foto</button>
    </div>
  </div>
</div>

<div id="vistaPreviaModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="vistaPreviaCredencial">
        <h3>Credencial Municipal</h3>
        <img id="credencialFoto" src="#" alt="Vista previa de la credencial">
        <p><span id="modalNombre"></span></p>
        <p><span id="modalRut"></span></p>
        <p><span id="modalCargo"></span></p>
        <p><span id="modalDepartamento"></span></p>
        <img id="codigoQRImg" src="#" alt="Codigo QR" style="margin-top: 10px; width: 80px;">
    </div>
  </div>
</div>

<script>
    const startCameraButton = document.getElementById('startCameraButton');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const captureButtonModal = document.getElementById('captureButtonModal');
    const fotoMostrada = document.getElementById('fotoMostrada');
    const camaraModal = document.getElementById('camaraModal');
    const vistaPreviaModal = document.getElementById('vistaPreviaModal');
    const btnGenerarQR = document.getElementById('btnGenerarQR');
    const codigoQR = document.getElementById('codigoQR');
    const span = document.getElementsByClassName("close");
    const credencialFoto = document.getElementById('credencialFoto');
    const videoModal = document.getElementById('videoModal');
    let mediaStream = null;
    let isCameraOn = false;

    btnGenerarQR.addEventListener('click', generarQR);

    startCameraButton.addEventListener('click', () => {
        showCameraModal();
    });

    captureButtonModal.addEventListener('click', () => {
        const fotoWidth = 300; 
        const fotoHeight = 410; 
        canvas.width = fotoWidth;
        canvas.height = fotoHeight;

        const x = (fotoWidth - videoModal.videoWidth) / 2;
        const y = (fotoHeight - videoModal.videoHeight) / 2;

        canvas.getContext('2d').drawImage(videoModal, x, y, videoModal.videoWidth, videoModal.videoHeight);
        const fotoURL = canvas.toDataURL('image/jpeg');
        fotoMostrada.src = fotoURL;
        fotoMostrada.style.display = 'block';
        camaraModal.style.display = 'none';
        stopCamera(); 
        document.getElementById('fotoTemp').value = fotoURL;
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
        stopCamera();
        document.getElementById('fotoTemp').value = fotoURL; 
    });

    document.getElementById('btnVistaPrevia').addEventListener('click', () => {
        showCredencialPreviewModal();
    });

    span[0].onclick = function() {
        closeModal(camaraModal);
    }

    span[1].onclick = function() {
        closeModal(vistaPreviaModal);
    }

    window.onclick = function(event) {
        if (event.target == camaraModal) {
            closeModal(camaraModal);
        }
        if (event.target == vistaPreviaModal) {
            closeModal(vistaPreviaModal);
        }
    }

    function generarQR() {
    const credencialNombre = document.getElementById('nombre').value;
    const credencialRut = document.getElementById('rut').value;
    const credencialCargo = document.getElementById('cargo').value;
    const credencialDepartamento = document.getElementById('departamento').value;

    const datosCredencial = `Nombre: ${credencialNombre}, Rut: ${credencialRut}, Cargo: ${credencialCargo}, Departamento: ${credencialDepartamento}`;
    
    const qr = new QRious({
        value: datosCredencial,
        size: 100
    });

    const qrImg = document.getElementById('codigoQRImg');
    qrImg.src = qr.toDataURL();

    document.getElementById('rutaQR').value = qr.toDataURL();
}



    function showCameraModal() {
        camaraModal.style.display = 'block';
        vistaPreviaModal.style.display = 'none';
        if (!isCameraOn) {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                mediaStream = stream;
                videoModal.srcObject = mediaStream;
                videoModal.play();
                isCameraOn = true;
            })
            .catch(error => {
                console.error('Error al acceder a la cámara.', error);
            });
        } else {
            stopCamera(); 
        }
    }

    function showCredencialPreviewModal() {
        const credencialNombre = document.getElementById('nombre').value;
        const credencialRut = document.getElementById('rut').value;
        const credencialCargo = document.getElementById('cargo').value;
        const credencialDepartamento = document.getElementById('departamento').value;
        
        document.getElementById("modalNombre").textContent = credencialNombre;
        document.getElementById("modalRut").textContent = credencialRut;
        document.getElementById("modalCargo").textContent = credencialCargo;
        document.getElementById("modalDepartamento").textContent = credencialDepartamento;
        credencialFoto.src = fotoMostrada.src;

        camaraModal.style.display = 'none';
        vistaPreviaModal.style.display = 'block';
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => {
                track.stop();
            });
        }
        videoModal.srcObject = null;
        isCameraOn = false;
    }

    function closeModal(modal) {
        modal.style.display = "none";
        stopCamera(); 
    }
</script>
</body>
</html>
















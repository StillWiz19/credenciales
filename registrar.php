<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Credenciales</title>
<link rel="stylesheet" href="styles.css">
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
    background-image: url('https://upload.wikimedia.org/wikipedia/commons/f/f4/Escudo_de_Angol.svg');
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

    }

    #vistaPreviaCredencial img {
        
        width: 2cm;
        height: 3cm;
        margin-bottom: 10px;
    }

    #vistaPreviaCredencial p {
        margin: 0;
        color: black; 
    }

    #camara {
        display: none;
    }

    #videoModal {
        width: 100%;
        height: auto;
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
        
        <input type="submit" value="Registrar">
    </form>
</div>


<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="vistaPreviaCredencial">
        <h3>Credencial Municipal</h3>
        <img id="credencialFoto" src="#" alt="Vista previa de la credencial">
        <p>Nombre: <span id="modalNombre"></span></p>
        <p>Rut: <span id="modalRut"></span></p>
        <p>Cargo: <span id="modalCargo"></span></p>
        <p>Departamento: <span id="modalDepartamento"></span></p>
    </div>
    <div id="camara">
        <video id="videoModal" autoplay></video>
        <button id="captureButtonModal" type="button">Capturar Foto</button>
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
    const modal = document.getElementById("myModal");
    const span = document.getElementsByClassName("close")[0];
    const credencialFoto = document.getElementById('credencialFoto');
    const videoModal = document.getElementById('videoModal');
    const camaraDiv = document.getElementById('camara');
    const vistaPreviaCredencialDiv = document.getElementById('vistaPreviaCredencial');
    let mediaStream = null;
    let isCameraOn = false;

    startCameraButton.addEventListener('click', () => {
        showCamera();
    });

    captureButtonModal.addEventListener('click', () => {
        const fotoWidth = 260; 
        const fotoHeight = 320; 
        canvas.width = fotoWidth;
        canvas.height = fotoHeight;

        const x = (fotoWidth - videoModal.videoWidth) / 2;
        const y = (fotoHeight - videoModal.videoHeight) / 2;

        canvas.getContext('2d').drawImage(videoModal, x, y, videoModal.videoWidth, videoModal.videoHeight);
        const fotoURL = canvas.toDataURL('image/jpeg');
        fotoMostrada.src = fotoURL;
        fotoMostrada.style.display = 'block';
        modal.style.display = 'none';
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
        showCredencialPreview();
    });

    span.onclick = function() {
        modal.style.display = "none";
        camaraDiv.style.display = 'none';
        vistaPreviaCredencialDiv.style.display = 'block';
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => {
                track.stop();
            });
        }
        isCameraOn = false;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            camaraDiv.style.display = 'none';
            vistaPreviaCredencialDiv.style.display = 'block';
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => {
                    track.stop();
                });
            }
            isCameraOn = false;
        }
    }

    function showCamera() {
        modal.style.display = 'block';
        camaraDiv.style.display = 'block';
        vistaPreviaCredencialDiv.style.display = 'none';
        if (!isCameraOn) {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                mediaStream = stream;
                videoModal.srcObject = mediaStream;
                videoModal.play();
                startCameraButton.textContent = 'Cerrar Cámara';
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
            videoModal.srcObject = null;
            startCameraButton.textContent = 'Abrir Cámara';
            isCameraOn = false;
        }
    }

    function showCredencialPreview() {
        const credencialNombre = document.getElementById('nombre').value;
        const credencialRut = document.getElementById('rut').value;
        const credencialCargo = document.getElementById('cargo').value;
        const credencialDepartamento = document.getElementById('departamento').value;
        
        document.getElementById("modalNombre").textContent = credencialNombre;
        document.getElementById("modalRut").textContent = credencialRut;
        document.getElementById("modalCargo").textContent = credencialCargo;
        document.getElementById("modalDepartamento").textContent = credencialDepartamento;
        credencialFoto.src = fotoMostrada.src;

        modal.style.display = "block";
        camaraDiv.style.display = 'none';
        vistaPreviaCredencialDiv.style.display = 'block';
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => {
                track.stop();
            });
        }
        isCameraOn = false;
    }
</script>
</body>
</html>













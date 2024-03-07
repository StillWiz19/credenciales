<?php 
require 'lib/phpqrcode.php';

$nombre = $_POST['nombre'] ?? '';
$rut = $_POST['rut'] ?? '';
$cargo = $_POST['cargo'] ?? '';
$departamento = $_POST['departamento'] ?? '';

$datos = "Nombre: $nombre\nRut: $rut\nCargo: $cargo\nDepartamento: $departamento";

QRcode::png($datos, 'qrcodes/credencial.png');
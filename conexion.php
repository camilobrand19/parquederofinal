<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parqueadero";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$controlador = new ControladorVehiculos($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST["placa"];
    $marca = $_POST["marca"];
    $color = $_POST["color"];
    $nombre = $_POST["nombre"];
    $documento = $_POST["documento"];
    $tipo = $_POST["tipo"];
    $accion = $_POST["accion"];

    $cliente = new Cliente($nombre, $documento);
    $vehiculo = ($tipo == 1) ? new Automovil($placa, $marca, $color) : new Motocicleta($placa, $marca, $color);

    if ($accion == "entrada") {
        $controlador->ingresarVehiculo($vehiculo, $cliente);
    } elseif ($accion == "salida") {
        $controlador->registrarSalida($placa);
    }
}

$conn->close();
?>

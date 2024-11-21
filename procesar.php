<?php
require_once 'autoload.php';

$conn = new mysqli("localhost", "root", "", "parqueadero");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$controlador = new ControladorVehiculos($conn);

$placa = $_POST["placa"];
$marca = $_POST["marca"];
$color = $_POST["color"];
$nombre = $_POST["nombre"];
$documento = $_POST["documento"];
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null; // Comprobar si "tipo" está definido
$accion = $_POST["accion"];

if ($tipo === null && $accion == "entrada") {
    die("Tipo de vehículo no especificado.");
}

$cliente = new Cliente($nombre, $documento);
$vehiculo = ($tipo == 1) ? new Automovil($placa, $marca, $color) : new Motocicleta($placa, $marca, $color);
$tipo_texto = ($tipo == 1) ? "Automóvil" : "Motocicleta";

if ($accion == "entrada") {
    $controlador->ingresarVehiculo($vehiculo, $cliente, $tipo_texto);
    header("Location: index.php");
    exit();
} elseif ($accion == "salida") {
    $precio = $controlador->registrarSalida($placa);
    header("Location: index.php?precio=$precio");
    exit();
}

$conn->close();
?>
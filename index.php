<?php
require_once 'autoload.php';
$precio = isset($_GET['precio']) ? $_GET['precio'] : null;
$conn = new mysqli("localhost", "root", "", "parqueadero");
$controlador = new ControladorVehiculos($conn);
$ocupacion = $controlador->getOcupacion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vehículos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Entrada y Salida de Vehículos</h2>
        <?php if ($precio !== null): ?>
            <div class="alert alert-success">Precio a pagar: COP $<?php echo number_format($precio, 2, ',', '.'); ?></div>
        <?php endif; ?>
        <form method="post" action="procesar.php">
            <div class="form-group">
                <label for="placa">Placa:</label>
                <input type="text" id="placa" name="placa" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del cliente:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="documento">Documento del cliente:</label>
                <input type="text" id="documento" name="documento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Vehículo:</label>
                <div>
                    <input type="radio" id="tipo_automovil" name="tipo" value="1" checked> <label for="tipo_automovil">Automóvil</label>
                    <input type="radio" id="tipo_motocicleta" name="tipo" value="2"> <label for="tipo_motocicleta">Motocicleta</label>
                </div>
            </div>
            <div class="form-group">
                <label for="accion">Acción:</label>
                <div>
                    <input type="radio" id="accion_entrada" name="accion" value="entrada" checked> <label for="accion_entrada">Entrada</label>
                    <input type="radio" id="accion_salida" name="accion" value="salida"> <label for="accion_salida">Salida</label>
                </div>
            </div>
            <button type="submit">Registrar</button>
        </form>

        <h2 class="mt-5">Ocupación del Parqueadero</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <th>Puesto <?php echo $i; ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php for ($i = 1; $i <= 10; $i++): 
                            $ocupado = false;
                            foreach ($ocupacion as $vehiculo) {
                                if ($vehiculo['puesto_id'] == $i) {
                                    $ocupado = true;
                                    break;
                                }
                            }
                            $clase = $ocupado ? 'table-danger' : 'table-success'; 
                        ?>
                            <td class="<?php echo $clase; ?>">
                                <?php 
                                    if ($ocupado) {
                                        echo 'Ocupado<br>Placa: ' . $vehiculo['placa'] . '<br>Ingreso: ' . $vehiculo['entrada'] . '<br>Propietario: ' . $vehiculo['nombre'];
                                    } else {
                                        echo 'Libre';
                                    }
                                ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


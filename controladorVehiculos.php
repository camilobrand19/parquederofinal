<?php
class ControladorVehiculos {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function ingresarVehiculo($vehiculo, $cliente, $tipo) {
        $vehiculo->registrarEntrada();

        // Verificar y ocupar un puesto libre secuencialmente
        $sqlPuestoLibre = "SELECT id FROM puestos WHERE ocupado = FALSE ORDER BY id ASC LIMIT 1";
        $resultPuestoLibre = $this->conexion->query($sqlPuestoLibre);

        if ($resultPuestoLibre->num_rows > 0) {
            $puesto = $resultPuestoLibre->fetch_assoc()['id'];

            $stmtCliente = $this->conexion->prepare("INSERT INTO clientes (nombre, documento) VALUES (?, ?)");
            $stmtCliente->bind_param("ss", $cliente->getNombre(), $cliente->getDocumento());
            $stmtCliente->execute();
            $cliente_id = $this->conexion->insert_id;

            $stmtOcuparPuesto = $this->conexion->prepare("UPDATE puestos SET ocupado = TRUE WHERE id = ?");
            $stmtOcuparPuesto->bind_param("i", $puesto);
            $stmtOcuparPuesto->execute();

            $stmtVehiculo = $this->conexion->prepare("INSERT INTO vehiculos (placa, marca, color, cliente_id, entrada, puesto_id, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtVehiculo->bind_param("sssisis", $vehiculo->getPlaca(), $vehiculo->getMarca(), $vehiculo->getColor(), $cliente_id, $vehiculo->getEntrada(), $puesto, $tipo);
            $stmtVehiculo->execute();

            echo "Vehículo ingresado con éxito. Puesto ocupado: $puesto\n";
        } else {
            echo "No hay espacios disponibles en el parqueadero.\n";
        }
    }

    public function registrarSalida($placa) {
        $vehiculoSalida = $this->buscarVehiculo($placa);
        if ($vehiculoSalida) {
            // Calcular el precio
            $entrada = strtotime($vehiculoSalida['entrada']);
            $salida = strtotime(date("Y-m-d H:i:s"));
            $diferenciaSegundos = $salida - $entrada;
            $diferenciaHoras = $diferenciaSegundos / 3600;

            // Calcular el precio según la duración de la estancia
            $precioPorHoraUSD = 5; // Precio por hora en dólares
            $tasaCambio = 4000; // Tasa de cambio USD a COP
            $precioPorHoraCOP = $precioPorHoraUSD * $tasaCambio;

            if ($diferenciaHoras <= 0.5) {
                $precio = round($precioPorHoraCOP / 2, 2); // Media hora
            } else {
                $precio = round(ceil($diferenciaHoras) * $precioPorHoraCOP, 2); // Por hora
            }

            // Actualizar el estado del puesto a libre
            $stmtLiberarPuesto = $this->conexion->prepare("UPDATE puestos SET ocupado = FALSE WHERE id = ?");
            $stmtLiberarPuesto->bind_param("i", $vehiculoSalida['puesto_id']);
            $stmtLiberarPuesto->execute();

            // Eliminar el vehículo de la base de datos
            $stmtEliminarVehiculo = $this->conexion->prepare("DELETE FROM vehiculos WHERE placa = ?");
            $stmtEliminarVehiculo->bind_param("s", $placa);
            $stmtEliminarVehiculo->execute();

            return $precio;
        } else {
            return null;
        }
    }

    public function buscarVehiculo($placa) {
        $stmt = $this->conexion->prepare("SELECT v.*, c.nombre, c.documento, v.puesto_id FROM vehiculos v JOIN clientes c ON v.cliente_id = c.id WHERE v.placa = ? AND v.salida IS NULL");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOcupacion() {
        $sql = "SELECT v.puesto_id, v.placa, v.marca, v.color, v.entrada, c.nombre, v.tipo 
                FROM vehiculos v 
                JOIN clientes c ON v.cliente_id = c.id 
                WHERE v.salida IS NULL";
        $result = $this->conexion->query($sql);
        $ocupacion = [];
        while ($row = $result->fetch_assoc()) {
            $ocupacion[] = $row;
        }
        return $ocupacion;
    }
}
?>
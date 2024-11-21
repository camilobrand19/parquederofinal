<?php
require_once 'vehiculo.php';

class Motocicleta extends Vehiculo {
    public function __construct($placa, $marca, $color) {
        parent::__construct($placa, $marca, $color);
    }

    // Otros métodos específicos de Motocicleta si es necesario
}
?>

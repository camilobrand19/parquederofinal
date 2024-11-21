<?php
require_once 'vehiculo.php';

class Automovil extends Vehiculo {
    public function __construct($placa, $marca, $color) {
        parent::__construct($placa, $marca, $color);
    }

    // Otros métodos específicos de Automóvil si es necesario
}
?>

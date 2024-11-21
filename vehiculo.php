<?php
class Vehiculo {
    protected $placa;
    protected $marca;
    protected $color;
    protected $entrada;
    protected $salida;

    public function __construct($placa, $marca, $color) {
        $this->placa = $placa;
        $this->marca = $marca;
        $this->color = $color;
        $this->entrada = date('Y-m-d H:i:s');
        $this->salida = null;
    }

    public function getPlaca() {
        return $this->placa;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getColor() {
        return $this->color;
    }

    public function getEntrada() {
        return $this->entrada;
    }

    public function getSalida() {
        return $this->salida;
    }

    public function registrarEntrada() {
        $this->entrada = date('Y-m-d H:i:s');
    }

    public function registrarSalida() {
        $this->salida = date('Y-m-d H:i:s');
    }
}
?>

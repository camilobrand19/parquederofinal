<?php
class Cliente {
    private $nombre;
    private $documento;

    public function __construct($nombre, $documento) {
        $this->nombre = $nombre;
        $this->documento = $documento;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDocumento() {
        return $this->documento;
    }
}
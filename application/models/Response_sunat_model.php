<?php
class Response_sunat_model {

    private $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', 'meliodas2214', 'ewgcgdaj_instituto');
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function save_files($xml, $cdr) {
        $xmlFileName = $this->save_file($xml, 'xml');
        $cdrFileName = $this->save_file($cdr, 'cdr');
        $query = "INSERT INTO response_sunat (xml, cdr) VALUES ('$xmlFileName', '$cdrFileName')";
        return $this->db->query($query);
    }

    private function save_file($fileData, $fileType) {
        $fileName = uniqid() . '.' . $fileType;
        $filePath = 'uploads/' . $fileName;
        file_put_contents($filePath, $fileData);
        return $fileName;
    }

    public function get_all() {
        $query = "SELECT * FROM response_sunat";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_by_id($id) {
        $query = "SELECT * FROM response_sunat WHERE id = " . $this->db->real_escape_string($id);
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function __destruct() {
        $this->db->close();
    }
}

// Uso del modelo
$model = new Response_sunat_model();

// Guardar archivos
$xml = file_get_contents('archivo.xml');
$cdr = file_get_contents('archivo.cdr');
$result = $model->save_files($xml, $cdr);
var_dump($result);

// Obtener todos los registros
$data = $model->get_all();
var_dump($data);

// Obtener un registro por ID
$data = $model->get_by_id(1);
var_dump($data);

<?php
/*
namespace App\Models;

use CodeIgniter\Model;

class Type_response_sunat_model extends Model
{
    protected $table      = 'type_response_sunat';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->initData();
    }

    protected function initData()
    {
        $data = [
            ['name' => 'Aceptado'],
            ['name' => 'Rechazado'],
            ['name' => 'Observaciones'],
        ];

        foreach ($data as $row) {
            $this->insert($row);
        }
    }
}*/

class Type_response_sunat_model {

    private $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', 'meliodas2214', 'ewgcgdaj_instituto');
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function get_all() {
        $query = "SELECT * FROM type_response_sunat";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_by_id($id) {
        $query = "SELECT * FROM type_response_sunat WHERE id = " . $this->db->real_escape_string($id);
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function insert($name) {
        $query = "INSERT INTO type_response_sunat (name) VALUES ('" . $this->db->real_escape_string($name) . "')";
        return $this->db->query($query);
    }

    public function update($id, $name) {
        $query = "UPDATE type_response_sunat SET name = '" . $this->db->real_escape_string($name) . "' WHERE id = " . $this->db->real_escape_string($id);
        return $this->db->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM type_response_sunat WHERE id = " . $this->db->real_escape_string($id);
        return $this->db->query($query);
    }

    public function initData() {
        $data = [
            ['name' => 'Aceptado'],
            ['name' => 'Rechazado'],
            ['name' => 'Observaciones'],
        ];

        foreach ($data as $row) {
            $this->insert($row['name']);
        }
    }

    public function __destruct() {
        $this->db->close();
    }
}

/*
// Uso del modelo
$model = new Type_response_sunat_model();

// Obtener todos los registros
$data = $model->get_all();
var_dump($data);

// Obtener un registro por ID
$data = $model->get_by_id(1);
var_dump($data);

// Insertar un nuevo registro
$result = $model->insert("Aceptado");
var_dump($result);

// Actualizar un registro
$result = $model->update(1, "Modificado");
var_dump($result);

// Eliminar un registro
$result = $model->delete(1);
var_dump($result);
*/
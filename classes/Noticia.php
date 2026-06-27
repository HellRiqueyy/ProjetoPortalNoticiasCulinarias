<?php
class Noticia {
    private $conn;
    private $table_name = "noticias";
    public function __construct($banco) {
        $this->conn = $banco;
    }

    public function criarNoticia($titulo, $noticia, $data, $imagem) {
        $autor = $_SESSION['usuario_nome'];
        $query = "INSERT INTO " . $this->table_name . " (titulo, noticia, data, autor, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("ssss", $titulo, $noticia, $data, $autor, $imagem);
        $stmt->execute();

        return $stmt;
    }

    public function lerNoticias() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public function deletarNoticia($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt;
    }
}
?>
<?php
class Noticia {
    private $conn;
    private $table_name = "noticias";
    public function __construct($banco) {
        $this->conn = $banco;
    }

    public function criarNoticia($titulo, $noticia, $imagem) {
        $autor = $_SESSION['usuario_id'];
        date_default_timezone_set('America/Sao_Paulo');
        $data = date('Y-m-d H:i:s');
        $query = "INSERT INTO " . $this->table_name . " (titulo, noticia, data, autor, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("sssis", $titulo, $noticia, $data, $autor, $imagem);
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
public function lerNoticiaPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function lerNoticiasPorAutor() {
        $autor_id = $_SESSION['usuario_id'];
        $query = "SELECT * FROM " . $this->table_name . " WHERE autor = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $autor_id);
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
    public function atualizarNoticia($id, $titulo, $noticia) {
        $query = "UPDATE " . $this->table_name . " SET titulo = ?, noticia = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("ssi", $titulo, $noticia, $id);
        $stmt->execute();
        return $stmt;
    }
}
?>
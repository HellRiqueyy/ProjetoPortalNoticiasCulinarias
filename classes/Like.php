<?php
class Like
{
    private $conn;
    private $table_name = "likes_noticias";

    public function __construct($banco)
    {
        $this->conn = $banco;
    }

    public function lerLikePorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE noticia_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function usuarioCurtiu($usuario_id, $noticia_id)
    {
        $query = "SELECT 1 FROM " . $this->table_name . " WHERE usuario_id = ? AND noticia_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("ii", $usuario_id, $noticia_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result && $result->num_rows > 0;
    }

    public function contarLikes($noticia_id)
    {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name . " WHERE noticia_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("i", $noticia_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int) ($row['total'] ?? 0);
    }

    public function curtir($usuario_id, $noticia_id)
    {
        if (empty($usuario_id) || empty($noticia_id)) {
            return false;
        }

        if ($this->usuarioCurtiu($usuario_id, $noticia_id)) {
            return true;
        }

        $query = "INSERT INTO " . $this->table_name . " (usuario_id, noticia_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("ii", $usuario_id, $noticia_id);
        return $stmt->execute();
    }

    public function descurtir($usuario_id, $noticia_id)
    {
        if (empty($usuario_id) || empty($noticia_id)) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE usuario_id = ? AND noticia_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }
        $stmt->bind_param("ii", $usuario_id, $noticia_id);
        return $stmt->execute();
    }

    public function alternarLike($usuario_id, $noticia_id)
    {
        if ($this->usuarioCurtiu($usuario_id, $noticia_id)) {
            return $this->descurtir($usuario_id, $noticia_id);
        }

        return $this->curtir($usuario_id, $noticia_id);
    }
}
?>
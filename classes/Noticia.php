<?php
class Noticia
{
    private $conn;
    private $table_name = "noticias";
    public function __construct($banco)
    {
        $this->conn = $banco;
    }
    public function resumirTexto($texto, $limite)
    {
        // Se o texto já for menor que o limite, devolve ele inteiro
        if (mb_strlen($texto) <= $limite) {
            return $texto;
        }

        // Limita o texto pelo número de caracteres
        $subtexto = mb_substr($texto, 0, $limite);

        // Procura a posição do último espaço para não cortar uma palavra no meio
        $ultimoEspaco = mb_strrpos($subtexto, ' ');

        // Corta o texto no último espaço encontrado e adiciona as reticências
        return mb_substr($subtexto, 0, $ultimoEspaco) . '...';
    }
    public function resolverImagemUrl($imagem)
    {
        if (empty($imagem)) {
            return '';
        }

        if (preg_match('#^https?://#', $imagem)) {
            return $imagem;
        }

        $caminho = str_replace('\\', '/', $imagem);
        $caminho = trim($caminho, '/');
        $caminho = preg_replace('#^\.\./+#', '', $caminho);
        $caminho = preg_replace('#^public/+#', '', $caminho);
        $caminho = 'public/' . $caminho;

        $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
            ? rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/')
            : '';
        $projectRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

        $basePath = '';
        if ($documentRoot && strpos($projectRoot, $documentRoot) === 0) {
            $basePath = substr($projectRoot, strlen($documentRoot));
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . $host . rtrim($basePath, '/') . '/' . ltrim($caminho, '/');
    }

    public function criarNoticia($titulo, $noticia, $imagem)
    {
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

    public function lerNoticias()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
    public function lerNoticiasPorData()
    {
        $query = "SELECT noticias.id, noticias.titulo, noticias.noticia, noticias.data, noticias.imagem, usuarios.nome as autorNome FROM "
            . $this->table_name . " JOIN usuarios ON noticias.autor = usuarios.id ORDER BY data DESC";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
    public function lerNoticiaPorId($id)
    {
        $query = "SELECT  noticias.id, noticias.titulo, noticias.data, noticias.noticia, noticias.imagem, usuarios.nome as autorNome FROM "
            . $this->table_name . " JOIN usuarios ON noticias.autor = usuarios.id WHERE noticias.id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function lerNoticiasPorAutor()
    {
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
    public function deletarNoticia($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt;
    }
    public function atualizarNoticia($id, $titulo, $noticia, $imagem = null)
    {
        if ($imagem === null) {
            $query = "UPDATE " . $this->table_name . " SET titulo = ?, noticia = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
            }

            $stmt->bind_param("ssi", $titulo, $noticia, $id);
        } else {
            $query = "UPDATE " . $this->table_name . " SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
            }

            $stmt->bind_param("sssi", $titulo, $noticia, $imagem, $id);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>
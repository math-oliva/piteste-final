<?php
session_start();
print_r($_SESSION);
require_once('conexao.php'); // Conexão com o banco

// Verifica se o tatuador está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'tatuador') {
    header("Location: login_tatuador.php");
    exit();
}

// ID do tatuador logado
$id_tatuador = $_SESSION['id_usuario'];

// Processar o upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'] ?? '';
    $imagem = $_FILES['imagem'];

    // Verifica se foi enviado um arquivo
    if ($imagem['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/portfolio/";
        $target_file = $target_dir . basename($imagem['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o tipo de arquivo é válido
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            // Move o arquivo para o diretório de destino
            if (move_uploaded_file($imagem['tmp_name'], $target_file)) {
                // Insere no banco de dados
                $query = "INSERT INTO portfolio_tatuador (id_tatuador, imagem, descricao) 
                          VALUES ($id_tatuador, '$target_file', '$descricao')";
                if (mysqli_query($conn, $query)) {
                    echo "Imagem adicionada com sucesso!";
                } else {
                    echo "Erro ao salvar no banco de dados.";
                }
            } else {
                echo "Erro ao fazer upload da imagem.";
            }
        } else {
            echo "Formato de arquivo inválido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
        }
    } else {
        echo "Nenhuma imagem enviada.";
    }
}

// Buscar as imagens já enviadas
$query = "SELECT * FROM portfolio_tatuador WHERE id_tatuador = $id_tatuador";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Portfólio</title>
</head>
<body>
<h1>Gerenciar Portfólio</h1>

<!-- Formulário de Upload -->
<form action="gerenciar_portfolio.php" method="POST" enctype="multipart/form-data">
    <label for="imagem">Selecione uma imagem:</label>
    <input type="file" name="imagem" id="imagem" required>
    <br>
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao"></textarea>
    <br>
    <button type="submit">Adicionar ao Portfólio</button>
</form>

<h2>Imagens no Portfólio</h2>
<div>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div>
            <img src="<?php echo $row['imagem']; ?>" alt="Imagem do Portfólio" width="150">
            <p><?php echo $row['descricao']; ?></p>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>

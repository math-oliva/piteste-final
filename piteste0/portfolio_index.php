<?php
require_once('conexao.php'); // Conexão com o banco de dados

// Verifica se o ID do tatuador foi enviado via GET
if (!isset($_GET['id'])) {
    echo "Tatuador não especificado.";
    exit();
}

$id_tatuador = $_GET['id'];

// Busca as informações do tatuador
$query_tatuador = "SELECT * FROM tatuadores WHERE id = $id_tatuador";
$result_tatuador = mysqli_query($conn, $query_tatuador);
$tatuador = mysqli_fetch_assoc($result_tatuador);

// Busca o portfólio do tatuador
$query_portfolio = "SELECT * FROM portfolio_tatuador WHERE id_tatuador = $id_tatuador";
$result_portfolio = mysqli_query($conn, $query_portfolio);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Tatuador</title>
</head>
<body>
<h1>Perfil do Tatuador</h1>

<!-- Informações do Tatuador -->
<h2><?php echo $tatuador['nome']; ?></h2>
<p><strong>Bio:</strong> <?php echo $tatuador['bio']; ?></p>
<p><strong>Especialidades:</strong> <?php echo $tatuador['especialidades']; ?></p>

<h2>Portfólio</h2>
<div>
    <?php while ($row = mysqli_fetch_assoc($result_portfolio)): ?>
        <div>
            <img src="<?php echo $row['imagem']; ?>" alt="Imagem do Portfólio" width="150">
            <p><?php echo $row['descricao']; ?></p>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>

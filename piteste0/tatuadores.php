<?php
require_once('conexao.php'); // Conexão com o banco de dados

// Busca todos os tatuadores cadastrados
$query = "SELECT id, nome, foto, especialidades FROM tatuadores";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tatuadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .tatuadores {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .tatuador-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 250px;
        }
        .tatuador-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .tatuador-card h3 {
            margin: 10px 0 5px;
        }
        .tatuador-card p {
            margin: 5px 0;
            font-size: 14px;
        }
        .tatuador-card a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tatuadores</h1>
    <div class="tatuadores">
        <?php while ($tatuador = mysqli_fetch_assoc($result)): ?>
            <div class="tatuador-card">
                <img src="uploads/<?php echo $tatuador['foto']; ?>" alt="Foto de <?php echo $tatuador['nome']; ?>">
                <h3><?php echo $tatuador['nome']; ?></h3>
                <p><strong>Especialidades:</strong> <?php echo $tatuador['especialidades']; ?></p>
                <a href="portfolio_index.php?id=<?php echo $tatuador['id']; ?>">Perfil do Tatuador</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

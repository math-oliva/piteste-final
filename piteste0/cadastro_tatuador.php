<?php
session_start();
print_r($_SESSION);
include('conexao.php'); // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    
    // Verificar se o e-mail já está cadastrado
    $query_check_email = "SELECT * FROM tatuadores WHERE email = '$email'";
    $result_check_email = mysqli_query($conn, $query_check_email);

    
    // Verificar se o e-mail já existe na tabela 'tatuadores'
    if (mysqli_num_rows($result_check_email) > 0) {
        echo "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
    } else {
        // Criptografar a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir os dados do tatuador na tabela tatuadores
        $query = "INSERT INTO tatuadores (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
        
        // Verificar se a inserção foi bem-sucedida
        if (mysqli_query($conn, $query)) {
            echo "Cadastro realizado com sucesso!";
            // Redirecionar para a página de login ou painel
            header("Location: login_tatuador.php");
            exit();
        } else {
            // Caso haja um erro ao inserir os dados
            echo "Erro ao cadastrar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Tatuador</title>
</head>
<body>

<h1>Cadastro de Tatuador</h1>

<!-- Formulário de cadastro -->
<form action="cadastro_tatuador.php" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <label for="telefone">Telefone:</label>
    <input type="tel" name="telefone" id="telefone" required>

    
    <button type="submit">Cadastrar</button>
</form>

<!-- Link para a página de login do tatuador -->
<p>Já tem conta? <a href="login_tatuador.php">Faça login aqui</a></p>

</body>
</html>

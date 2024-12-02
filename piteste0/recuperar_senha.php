<?php
require_once('conexao.php'); // Conexão com o banco de dados

// Inicializa a mensagem de erro/sucesso
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $nova_senha = trim($_POST['nova_senha']);
    $nova_senha_confirmar = trim($_POST['nova_senha_confirmar']);

    // Verifica se os campos estão preenchidos
    if (empty($email) || empty($nova_senha) || empty($nova_senha_confirmar)) {
        $mensagem = 'Por favor, preencha todos os campos.';
    } elseif ($nova_senha !== $nova_senha_confirmar) {
        $mensagem = 'As senhas não coincidem. Tente novamente.';
    } else {
        // Busca o cliente pelo e-mail
        $query_cliente = "SELECT id FROM clientes WHERE email = '$email'";
        $result_cliente = mysqli_query($conn, $query_cliente);

        if (mysqli_num_rows($result_cliente) > 0) {
            // Cliente encontrado, atualiza a senha
            $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update_query = "UPDATE clientes SET senha = '$hash_senha' WHERE email = '$email'";

            if (mysqli_query($conn, $update_query)) {
                $mensagem = 'Senha alterada com sucesso! Faça login com a nova senha.';
            } else {
                $mensagem = 'Erro ao alterar a senha. Por favor, tente novamente.';
            }
        } else {
            $mensagem = 'E-mail não encontrado. Verifique e tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Recuperar Senha</h1>

<?php if (!empty($mensagem)): ?>
    <p><?php echo $mensagem; ?></p>
<?php endif; ?>

<form action="recuperar_senha.php" method="POST">
    <div>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>
    </div>
    <div>
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" id="nova_senha" required>
    </div>
    <div>
        <label for="nova_senha_confirmar">Confirmar Nova Senha:</label>
        <input type="password" name="nova_senha_confirmar" id="nova_senha_confirmar" required>
    </div>
    <button type="submit">Alterar Senha</button>
</form>

<p><a href="login_cliente.php">Voltar para o Login</a></p>

</body>
</html>

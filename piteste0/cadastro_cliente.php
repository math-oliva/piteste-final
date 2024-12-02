<?php
session_start();
print_r($_SESSION);
include('conexao.php');

// Função para gerar nome único para a foto
function gerarNomeUnico($extensao) {
    return uniqid() . '.' . $extensao;
}

// Processa o cadastro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];

    // Processando upload de foto
    $foto = NULL;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($extensao), $extensoes_validas)) {
            $nomeFoto = gerarNomeUnico($extensao);
            $caminhoFoto = 'uploads/' . $nomeFoto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto);
            $foto = $caminhoFoto;
        } else {
            $erro = "Apenas arquivos de imagem são permitidos (jpg, jpeg, png, gif).";
        }
    }

    // Criptografando a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserindo os dados no banco
    if (!isset($erro)) {
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone, data_nascimento, foto, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, 'cliente')");
        $stmt->bind_param("ssssss", $nome, $email, $senha_hash, $telefone, $data_nascimento, $foto);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
            header("Location: login_cliente.php");
            exit();
        } else {
            $erro = "Erro ao realizar o cadastro. Tente novamente.";
        }
    }
}
?>

<!-- Formulário de cadastro -->
<form method="POST" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <label for="telefone">Telefone:</label>
    <input type="text" name="telefone" id="telefone">

    <label for="data_nascimento">Data de Nascimento:</label>
    <input type="date" name="data_nascimento" id="data_nascimento" required>

    <label for="foto">Foto de Perfil:</label>
    <input type="file" name="foto" id="foto">

    <button type="submit">Cadastrar</button>
</form>

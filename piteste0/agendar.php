<?php

session_start();
print_r($_SESSION);
include('conexao.php'); // Conexão com o banco de dados

// Verificar se o cliente está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header('Location: login_cliente.php'); // Se não estiver logado ou não for cliente, redireciona para o login
    exit();
}

// Buscar todos os tatuadores
$query_tatuadores = "SELECT * FROM tatuadores";
$result_tatuadores = mysqli_query($conn, $query_tatuadores);

// Buscar todas as especialidades
$query_especialidades = "SELECT * FROM especialidades";
$result_especialidades = mysqli_query($conn, $query_especialidades);

// Se o formulário for enviado, salva o agendamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_SESSION['id_usuario'];
    $id_tatuador = $_POST['id_tatuador'];
    $data_agendamento = $_POST['data_agendamento'];
    $especialidades = implode(", ", $_POST['especialidades']); // Convertendo o array em uma string

    // Inserir o agendamento no banco de dados
    $query_agendamento = "INSERT INTO agendamentos (id_cliente, id_tatuador, data_agendamento, especialidades, status) 
                          VALUES ('$id_cliente', '$id_tatuador', '$data_agendamento', '$especialidades', 'pendente')";
    
    if (mysqli_query($conn, $query_agendamento)) {
        header("Location: perfil_cliente.php"); // Redireciona para o painel do cliente após o agendamento
        exit();
    } else {
        echo "Erro ao agendar a sessão.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Sessão de Tatuagem</title>
    <link href="https://fonts.googleapis.com/css2?family=Glass+Antiqua&family=Teko&display=swap" rel="stylesheet">
    <style>
        /* Estilo global */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Glass Antiqua', 'Teko', sans-serif;
            background: linear-gradient(to bottom, #8b0000, #000000); /* Degradê do vermelho para o preto */
            color: #ffffff; /* Texto branco */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(139, 0, 0, 0.85); /* Fundo semi-transparente vermelho escuro */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 700px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: scale(1.05); /* Efeito de ampliação ao passar o mouse */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        h1 {
            text-align: center;
            color: #ffffff;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 1.1rem;
        }

        select, input[type="datetime-local"] {
            padding: 12px;
            border: 2px solid #ffffff; /* Bordas brancas */
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 1rem;
            background-color: #000000; /* Fundo preto */
            color: #ffffff; /* Texto branco */
            transition: border-color 0.3s ease;
        }

        select:focus, input[type="datetime-local"]:focus {
            outline: none;
            border-color: #ffcccc; /* Bordas mudam ao foco */
        }

        button {
            padding: 12px;
            background-color: #ffffff; /* Fundo branco */
            color: #8b0000; /* Texto vermelho escuro */
            font-size: 1.3rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #8b0000;
            color: #ffffff;
            transform: scale(1.05); /* Efeito de ampliação no botão */
        }

        .back-link {
            margin-top: 30px;
            text-align: center;
        }

        .back-link a {
            color: #ffcccc; /* Link em vermelho claro */
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            text-decoration: underline;
            color: #ffffff;
        }

        .select-wrapper {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<h1>Agendar Sessão de Tatuagem</h1>

<!-- Formulário de agendamento -->
<form action="agendar.php" method="POST">

    <!-- Seleção do Tatuador -->
    <div>
        <label for="id_tatuador">Escolha o Tatuador:</label>
        <select name="id_tatuador" id="id_tatuador" required>
            <?php while ($tatuador = mysqli_fetch_assoc($result_tatuadores)): ?>
                <option value="<?php echo $tatuador['id']; ?>">
                    <?php echo $tatuador['nome']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Data do Agendamento -->
    <div>
        <label for="data_agendamento">Escolha a Data e Hora:</label>
        <input type="datetime-local" name="data_agendamento" id="data_agendamento" required>
    </div>

    <!-- Especialidades -->
    <div>
        <label for="especialidades">Escolha as Especialidades:</label>
        <select name="especialidades[]" id="especialidades" multiple required>
            <?php while ($especialidade = mysqli_fetch_assoc($result_especialidades)): ?>
                <option value="<?php echo $especialidade['nome']; ?>">
                    <?php echo $especialidade['nome']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Botão de Envio -->
    <button type="submit">Agendar</button>
</form>

<!-- Link para voltar ao painel do cliente -->
<p><a href="perfil_cliente.php">Voltar ao perfil</a></p>

</body>
</html>

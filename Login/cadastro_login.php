<?php
include '../conexao.php';  // Inclui o arquivo de conexão com o banco de dados
session_start();  // Inicia a sessão para acessar informações do usuário logado

// Verifica o tipo de usuário na sessão. Se não definido, assume uma string vazia.
$usuario_tipo = $_SESSION['cargo'] ?? '';

// Processa o cadastro se o botão 'cadastrar' for pressionado
if (isset($_POST['cadastrar'])) {
    // Obtém os dados enviados pelo formulário
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $cargo = $_POST['select'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $admin = isset($_POST['admin']) ? $_POST['admin'] : 'N';

    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($nome) || empty($email) || empty($cargo) || empty($cpf) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos'); window.location.href='cadastro_login.php';</script>";
        die();  // Interrompe a execução do script se algum campo estiver vazio
    }

    // Adiciona o novo usuário ao banco de dados com base no cargo selecionado
    if ($cargo == "Dentista") {
        // Insere o dentista na tabela 'dentista'
        $stmt = $mysqli->prepare("INSERT INTO dentista (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        $id_dentista = $stmt->insert_id;  // Obtém o ID do dentista recém-inserido
        $stmt->close();

        // Insere o usuário na tabela 'usuarios' com o ID do dentista
        $stmt = $mysqli->prepare("INSERT INTO usuarios (cpf, senha, cargo, id_dentista, admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $cpf, $senha, $cargo, $id_dentista, $admin);
    } elseif ($cargo == "Funcionario") {
        // Insere o funcionário na tabela 'secretaria'
        $stmt = $mysqli->prepare("INSERT INTO secretaria (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        $id_secretaria = $stmt->insert_id;  // Obtém o ID do funcionário recém-inserido
        $stmt->close();

        // Insere o usuário na tabela 'usuarios' com o ID do funcionário
        $stmt = $mysqli->prepare("INSERT INTO usuarios (cpf, senha, cargo, id_secretaria, admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $cpf, $senha, $cargo, $id_secretaria, $admin);
    }

    // Executa a query para adicionar o usuário e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='consulta.login.php';</script>";
    } else {
        echo "<script>alert('Não foi possível cadastrar esse usuário'); window.location.href='cadastro_login.php';</script>";
    }

    $stmt->close();  // Fecha a declaração SQL
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Inclui o CSS do Bootstrap e outras folhas de estilo -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/tooplate-style.css">
    <title>Cadastro Usuário</title>
</head>
<body>
    <section id="appointment" data-stellar-background-ratio="3">
        <div class="container">
            <div class="row">
                <!-- Imagem de fundo para a seção -->
                <div class="col-md-6 col-sm-6">
                    <img src="../images/appointment-image.jpg" class="img-responsive" alt="">
                </div>
                <div class="col-md-6 col-sm-6">
                    <!-- Formulário de cadastro de usuário -->
                    <form id="appointment-form" role="form" method="post" action="">
                        <div class="section-title wow fadeInUp" data-wow-delay="0.4s" id="news">
                            <h2>Cadastro de usuário</h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-delay="0.8s">
                            <!-- Campos do formulário -->
                            <div class="col-md-6 col-sm-6">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome..">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Seu E-mail">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="select">Cargo</label>
                                <select class="form-control" name="select">
                                    <option value="Funcionario">Funcionario</option>
                                    <option value="Dentista">Dentista</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="admin">Admin</label>
                                <select class="form-control" name="admin">
                                    <option value="N">Não</option>
                                    <option value="S">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="cpf">Login</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" title="Digite o CPF">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="senha">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha..">
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <!-- Botão para enviar o formulário -->
                                <button type="submit" class="form-control" id="cadastrar" name="cadastrar">Cadastrar</button>
                            </div>
                        </div>
                    </form>

                    <!-- Botão "Voltar" com base no tipo de usuário -->
                    <form method="get" action="<?php echo ($usuario_tipo == 'Dentista') ? 'tela_dentistas.php' : 'tela_funcionario.php'; ?>">
                        <button type="submit" class="form-control">Voltar</button>
                    </form>

                    <!-- Botão para consultar usuários -->
                    <form method="get" action="consulta.login.php">
                        <button type="submit" class="form-control">Consultar Usuários</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

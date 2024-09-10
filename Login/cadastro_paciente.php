<?php
include '../conexao.php';  // Inclui o arquivo de conexão com o banco de dados

session_start();  // Inicia a sessão para verificar informações do usuário logado

// Verifica se o usuário está autenticado e tem o cargo de 'Funcionario'
// Se não estiver autenticado ou o cargo não for 'Funcionario', redireciona para a página de login
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Funcionario') {
  header("Location: login2.php");
  exit();  // Encerra a execução do script para evitar acesso não autorizado
}

$message = "";  // Inicializa uma variável para armazenar mensagens

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtém os dados enviados pelo formulário
  $nome = $_POST['nome'];
  $cpf = $_POST['cpf'];
  $dt_nascimento = $_POST['dt_nascimento'];
  $telefone = $_POST['telefone'];
  $sexo = $_POST['sexo'];

  // SQL para inserir um novo paciente na tabela
  $sql = "INSERT INTO paciente (nome, cpf, dt_nascimento, telefone, sexo) VALUES ('$nome', '$cpf', '$dt_nascimento', '$telefone', '$sexo')";

  // Executa a query e verifica se foi bem-sucedida
  if (mysqli_query($mysqli, $sql)) {
    $message = "Novo paciente cadastrado com sucesso!";
    // Redireciona para a página de consulta de pacientes com parâmetro de sucesso
    header("Location: consulta_pacientes.php?success=1");
    exit();  // Encerra a execução após o redirecionamento
  } else {
    $message = "Erro: " . $sql . "<br>" . mysqli_error($mysqli);
    // Redireciona para a página de cadastro de paciente com parâmetro de erro
    header("Location: cadastro_paciente.php?success=0");
    exit();  // Encerra a execução após o redirecionamento
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Paciente - Bucal Saúde</title>
  <!-- Inclui o CSS do Bootstrap para estilizar a página -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- Inclui jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Inclui o JavaScript do Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <!-- Inclui SweetAlert2 para alertas mais bonitos -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Inclui o CSS adicional -->
  <link rel="stylesheet" href="../dentista/style.css">
</head>
<body>
  <section class="container mt-5">
    <h2 class="text-center mb-4">Cadastro de Paciente</h2>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="text-center mb-4">
              <!-- Imagem do paciente -->
              <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Imagem de Paciente" class="img-fluid rounded" style="max-width: 150px;">
            </div>
            <!-- Formulário para cadastrar um novo paciente -->
            <form action="cadastro_paciente.php" method="post">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
              </div>
              <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" required>
              </div>
              <div class="mb-3">
                <label for="dt_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="dt_nascimento" name="dt_nascimento" required>
              </div>
              <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" required>
              </div>
              <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo" required>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
              </div>
              <div class="d-flex justify-content-between">
                <!-- Botões para navegar para outras páginas ou enviar o formulário -->
                <a href="tela_funcionario.php" class="btn btn-secondary">Voltar</a>
                <a href="consulta_pacientes.php" class="btn btn-warning">Consultar Pacientes</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>

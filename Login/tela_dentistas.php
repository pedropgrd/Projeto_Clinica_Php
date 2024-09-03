<?php
include '../conexao.php';
session_start();

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Dentista') {
    header("Location: login2.php");
    exit();
}

$admin = $_SESSION['admin'];
$id_dentista = $_SESSION['id_dentista']; //  ID do dentista seja salvo na sessão

// Buscar o nome do dentista
$sql = "SELECT nome FROM dentista WHERE id_dentista = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_dentista);
$stmt->execute();
$stmt->bind_result($nome_dentista);
$stmt->fetch();
$stmt->close();

// Buscar agendamentos do dentista
$agendamentos = [];
$sql = "SELECT * FROM agendamento WHERE id_dentista = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_dentista);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bucal Saúde</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-4pXbTfwX9mzFBDL8YBvYoKT6hJ2hfXmxZ9pp4lT1A1Kb4wV7V9kO0dZ9ukcHbC4w" crossorigin="anonymous">

    <link rel="stylesheet" href="../dentista/style.css">

</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">

            <div class="me-3 ms-3">
          <a href="../index.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="40" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
              <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
            </svg></a>
        </div>
                <a class="navbar-brand" href="../index.php">
                    <i class="fa-solid fa-tooth"></i> Bucal Saúde
                </a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <?php if ($admin == 'S') : ?>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-none" href="cadastro_login.php">Administrador</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <span class="navbar-text ms-auto">
                        Bem-vindo, <?php echo htmlspecialchars($nome_dentista); ?>!
                    </span>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <h3>Seus Agendamentos</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Tipo de Consulta</th>
                    <th>Descrição</th>
                    <th>Realizado</th>
                    <th>Horário</th>
                    <th>Data</th>
                    <th>Preço Final</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($agendamentos)): ?>
                    <tr>
                        <td colspan="8">Nenhum agendamento encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($agendamento['id_agenda']); ?></td>
                            <td>
                                <?php
                                // Buscar nome do paciente usando $agendamento['id_paciente']
                                $stmt = $mysqli->prepare("SELECT nome FROM paciente WHERE id_paciente = ?");
                                $stmt->bind_param("i", $agendamento['id_paciente']);
                                $stmt->execute();
                                $stmt->bind_result($nome_paciente);
                                $stmt->fetch();
                                $stmt->close();
                                echo htmlspecialchars($nome_paciente);
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($agendamento['tipo_consulta']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['realizado']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['horario']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['dt_agenda']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['preco_final_agendamento']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

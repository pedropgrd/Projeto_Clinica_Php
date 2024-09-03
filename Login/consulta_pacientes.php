<?php
include '../conexao.php';

// Atualização dos Dados
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $dt_nascimento = $_POST['dt_nascimento'];
    $telefone = $_POST['telefone'];
    $sexo = $_POST['sexo'];

    // Atualizar na tabela de paciente
    $sql = "UPDATE paciente SET nome='$nome', cpf='$cpf', dt_nascimento='$dt_nascimento', telefone='$telefone', sexo='$sexo' WHERE id_paciente=$id";
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Paciente atualizado com sucesso'); window.location.href='consulta_pacientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar paciente: " . $mysqli->error . "'); window.location.href='consulta_pacientes.php';</script>";
    }
}

// Exclusão dos Dados
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM paciente WHERE id_paciente=$id";
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Paciente deletado com sucesso'); window.location.href='consulta_pacientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao deletar paciente: " . $mysqli->error . "'); window.location.href='consulta_pacientes.php';</script>";
    }
}

// Filtragem dos Dados
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Consulta de Pacientes</h1>

        <!-- Formulário de Busca -->
        <form class="d-flex mb-4" action="consulta_pacientes.php" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Buscar por nome" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data de Nascimento</th>
                    <th>Telefone</th>
                    <th>Sexo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM paciente WHERE nome LIKE '%$search%' ORDER BY nome ASC";
                $result = $mysqli->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id_paciente"] . "</td>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["cpf"] . "</td>";
                        echo "<td>" . $row["dt_nascimento"] . "</td>";
                        echo "<td>" . $row["telefone"] . "</td>";
                        echo "<td>" . ($row["sexo"] == 'M' ? 'Masculino' : 'Feminino') . "</td>";
                        echo "<td>
                                <form style='display:inline;' method='post' action='consulta_pacientes.php'>
                                    <input type='hidden' name='id' value='" . $row["id_paciente"] . "'>
                                    <input type='hidden' name='nome' value='" . $row["nome"] . "'>
                                    <input type='hidden' name='cpf' value='" . $row["cpf"] . "'>
                                    <input type='hidden' name='dt_nascimento' value='" . $row["dt_nascimento"] . "'>
                                    <input type='hidden' name='telefone' value='" . $row["telefone"] . "'>
                                    <input type='hidden' name='sexo' value='" . $row["sexo"] . "'>
                                    <button type='button' class='btn btn-primary' onclick='editPatient(this)'>Editar</button>
                                </form>
                                <form style='display:inline;' method='get' action='consulta_pacientes.php'>
                                    <input type='hidden' name='delete' value='" . $row["id_paciente"] . "'>
                                    <button type='submit' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja deletar este paciente?\")'>Deletar</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum paciente encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulário de Edição -->
        <div id="editForm" style="display:none;">
            <h2>Editar Paciente</h2>
            <form method="post" action="consulta_pacientes.php">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_nome">Nome</label>
                    <input type="text" class="form-control" id="edit_nome" name="nome">
                </div>
                <div class="form-group">
                    <label for="edit_cpf">CPF</label>
                    <input type="text" class="form-control" id="edit_cpf" name="cpf">
                </div>
                <div class="form-group">
                    <label for="edit_dt_nascimento">Data de Nascimento</label>
                    <input type="date" class="form-control" id="edit_dt_nascimento" name="dt_nascimento">
                </div>
                <div class="form-group">
                    <label for="edit_telefone">Telefone</label>
                    <input type="text" class="form-control" id="edit_telefone" name="telefone">
                </div>
                <div class="form-group">
                    <label for="edit_sexo">Sexo</label>
                    <select class="form-control" id="edit_sexo" name="sexo">
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                <button type="submit" name="update" class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>
    <div class="container p-3 m-3">
    <div class="row">
            <div class="col">
                <a href="cadastro_paciente.php" class="btn btn-primary mb-2">Adicionar novo Paciente</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <a href="tela_funcionario.php" class="btn btn-secondary mb-2">Voltar para Tela de Funcionário</a>
            </div>
        </div>
    </div>
    <script>
        function editPatient(button) {
            var form = button.closest('form');
            document.getElementById('edit_id').value = form.querySelector('').value;
            document.getElementById('edit_nome').value = form.querySelector('[name[name="id"]="nome"]').value;
            document.getElementById('edit_cpf').value = form.querySelector('[name="cpf"]').value;
            document.getElementById('edit_dt_nascimento').value = form.querySelector('[name="dt_nascimento"]').value;
            document.getElementById('edit_telefone').value = form.querySelector('[name="telefone"]').value;
            document.getElementById('edit_sexo').value = form.querySelector('[name="sexo"]').value;
            document.getElementById('editForm').style.display = 'block';
            window.scrollTo(0, document.getElementById('editForm').offsetTop);
        }
    </script>
</body>
</html>

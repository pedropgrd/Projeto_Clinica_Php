<?php
// Inclui o arquivo de conexão com o banco de dados
include '../conexao.php';

// Atualização dos Dados
if (isset($_POST['update'])) { 
    $id = $_POST['id']; 
    $nome = $_POST['name']; 
    $email = $_POST['email']; 
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha']; 
    $admin = $_POST['admin']; 

    // Atualiza os dados na tabela de usuários
    $sql = "UPDATE usuarios SET cpf=?, senha=?, admin=? WHERE id_usuario=?";
    $stmt = $mysqli->prepare($sql); // Prepara a query SQL
    $stmt->bind_param("sssi", $cpf, $senha, $admin, $id); // Vincula os parâmetros

    if ($stmt->execute()) { // Executa a query
        // Atualiza dados específicos de dentistas ou secretárias
        if ($_POST['cargo'] == "Dentista") { // Verifica o cargo
            $sql = "UPDATE dentista SET nome=?, email=? WHERE id_dentista=?";
            $stmt = $mysqli->prepare($sql); // Prepara a query SQL para dentistas
            $stmt->bind_param("ssi", $nome, $email, $id); // Vincula os parâmetros
            $stmt->execute(); // Executa a query
        } elseif ($_POST['cargo'] == "Funcionario") { // Verifica o cargo
            $sql = "UPDATE secretaria SET nome=?, email=? WHERE id_secretaria=?";
            $stmt = $mysqli->prepare($sql); // Prepara a query SQL para secretárias
            $stmt->bind_param("ssi", $nome, $email, $id); // Vincula os parâmetros
            $stmt->execute(); // Executa a query
        }

        // Mensagem de sucesso e redirecionamento
        echo "<script>alert('Registro atualizado com sucesso'); window.location.href='consulta.login.php';</script>";
    } else {
        // Mensagem de erro e redirecionamento
        echo "<script>alert('Erro ao atualizar registro: " . $stmt->error . "'); window.location.href='consulta.login.php';</script>";
    }

    $stmt->close(); // Fecha o statement
}

// Exclusão dos Dados
if (isset($_GET['delete'])) { // Verifica se a solicitação de exclusão foi feita
    $id = $_GET['delete']; // Obtém o ID do usuário a ser excluído

    // Deleta o usuário da tabela
    $sql = "DELETE FROM usuarios WHERE id_usuario=?";
    $stmt = $mysqli->prepare($sql); // Prepara a query SQL
    $stmt->bind_param("i", $id); // Vincula o parâmetro

    if ($stmt->execute()) { // Executa a query
        // Mensagem de sucesso e redirecionamento
        echo "<script>alert('Registro deletado com sucesso'); window.location.href='consulta.login.php';</script>";
    } else {
        // Mensagem de erro e redirecionamento
        echo "<script>alert('Erro ao deletar registro: " . $stmt->error . "'); window.location.href='consulta.login.php';</script>";
    }

    $stmt->close(); // Fecha o statement
}

// Filtragem dos Dados
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Obtém o termo de busca, se houver
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Consulta de Usuários</h1>

        <!-- Formulário de Busca -->
        <form class="d-flex mb-4" action="consulta.login.php" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Buscar por nome ou CPF" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>

        <!-- Tabela de Usuários -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Admin</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta SQL para obter os dados dos usuários com base na busca
                $sql = "SELECT u.id_usuario, u.cpf, u.senha, u.cargo, u.admin, 
                               COALESCE(d.nome, s.nome) AS nome, 
                               COALESCE(d.email, s.email) AS email
                        FROM usuarios u
                        LEFT JOIN dentista d ON u.id_dentista = d.id_dentista
                        LEFT JOIN secretaria s ON u.id_secretaria = s.id_secretaria
                        WHERE d.nome LIKE ? OR s.nome LIKE ? OR u.cpf LIKE ?
                        ORDER BY nome ASC";
                $stmt = $mysqli->prepare($sql); // Prepara a query SQL
                $searchParam = "%$search%"; // Adiciona os % para a busca parcial
                $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam); // Vincula os parâmetros
                $stmt->execute(); // Executa a query
                $result = $stmt->get_result(); // Obtém o resultado da query

                if ($result->num_rows > 0) { // Verifica se há resultados
                    while ($row = $result->fetch_assoc()) { // Itera sobre os resultados
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id_usuario"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["cpf"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["cargo"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["admin"]) . "</td>";
                        echo "<td>
                                <button class='btn btn-primary btn-sm' onclick='editUser(" . json_encode($row) . ")'>Editar</button>
                                <form style='display:inline;' method='get' action='consulta.login.php'>
                                    <input type='hidden' name='delete' value='" . $row["id_usuario"] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja deletar este usuário?\")'>Deletar</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum usuário encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulário de Edição -->
        <div id="editForm" class="mt-4" style="display:none;">
            <h2>Editar Usuário</h2>
            <form method="post" action="consulta.login.php">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group mb-3">
                    <label for="edit_name">Nome</label>
                    <input type="text" class="form-control" id="edit_name" name="name">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_email">Email</label>
                    <input type="email" class="form-control" id="edit_email" name="email">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_cpf">CPF</label>
                    <input type="text" class="form-control" id="edit_cpf" name="cpf">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_senha">Senha</label>
                    <input type="password" class="form-control" id="edit_senha" name="senha">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_admin">Admin</label>
                    <select class="form-control" id="edit_admin" name="admin">
                        <option value="N">Não</option>
                        <option value="S">Sim</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="edit_cargo">Cargo</label>
                    <input type="text" class="form-control" id="edit_cargo" name="cargo" readonly>
                </div>
                <button type="submit" name="update" class="btn btn-success">Salvar</button>
            </form>
        </div>

        <!-- Botões de Ação -->
        <div class="mb-4">
            <a href="cadastro_login.php" class="btn btn-secondary">Voltar para Cadastro</a>
        </div>
    </div>

    <script>
        // Função para preencher o formulário de edição com os dados do usuário
        function editUser(user) {
            document.getElementById('edit_id').value = user.id_usuario;
            document.getElementById('edit_name').value = user.nome;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_cpf').value = user.cpf;
            document.getElementById('edit_senha').value = user.senha;
            document.getElementById('edit_admin').value = user.admin;
            document.getElementById('edit_cargo').value = user.cargo;
            document.getElementById('editForm').style.display = 'block'; // Exibe o formulário de edição
            window.scrollTo(0, document.getElementById('editForm').offsetTop); // Rola a página para o formulário
        }
    </script>
</body>
</html>

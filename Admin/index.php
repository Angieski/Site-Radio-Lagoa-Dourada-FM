<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexao.php';
verificarLogin();

unset($_SESSION['success']);
unset($_SESSION['error']);

// Processar operações CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ADICIONAR PROGRAMA
        if (isset($_POST['add_programa'])) {
            $stmt = $conn->prepare("INSERT INTO programas 
                (nome, inicio, dia, grupo) 
                VALUES (?, ?, ?, 'RdsLag')");
            
            $stmt->bind_param("ssi", 
                $_POST['nome'],
                $_POST['inicio'],
                $_POST['dia']
            );
            
            $stmt->execute();
            $_SESSION['success'] = "Programa adicionado!";
        }
        
        // EDITAR PROGRAMA
        elseif (isset($_POST['edit_programa'])) {
            $stmt = $conn->prepare("UPDATE programas SET 
                nome = ?, 
                inicio = ?, 
                dia = ? 
                WHERE id = ?");
            
            $stmt->bind_param("ssii",
                $_POST['nome'],
                $_POST['inicio'],
                $_POST['dia'],
                $_POST['id']
            );
            
            $stmt->execute();
            $_SESSION['success'] = "Programa atualizado!";
        }
        
        // EXCLUIR PROGRAMA
        elseif (isset($_POST['delete_programa'])) {
            $stmt = $conn->prepare("DELETE FROM programas WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            $_SESSION['success'] = "Programa excluído!";
        }
        
        // EQUIPE LAGOA
        if (isset($_POST['add_equipelagoa'])) {
            $stmt = $conn->prepare("INSERT INTO equipelagoa (nome, cargo) VALUES (?, ?)");
            $stmt->bind_param("ss", $_POST['nome'], $_POST['cargo']);
            $stmt->execute();
            $_SESSION['success'] = "Membro adicionado!";
        }
        
        elseif (isset($_POST['edit_equipelagoa'])) {
            $stmt = $conn->prepare("UPDATE equipelagoa SET nome = ?, cargo = ? WHERE id = ?");
            $stmt->bind_param("ssi", $_POST['nome'], $_POST['cargo'], $_POST['id']);
            $stmt->execute();
            $_SESSION['success'] = "Membro atualizado!";
        }
        
        elseif (isset($_POST['delete_equipelagoa'])) {
            $stmt = $conn->prepare("DELETE FROM equipelagoa WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            $_SESSION['success'] = "Membro excluído!";
        }
        
        // EQUIPE ESPORTE
        if (isset($_POST['add_eqpesporte'])) {
            $stmt = $conn->prepare("INSERT INTO eqpesporte (nome, cargo) VALUES (?, ?)");
            $stmt->bind_param("ss", $_POST['nome'], $_POST['cargo']);
            $stmt->execute();
            $_SESSION['success'] = "Membro adicionado!";
        }
        
        elseif (isset($_POST['edit_eqpesporte'])) {
            $stmt = $conn->prepare("UPDATE eqpesporte SET nome = ?, cargo = ? WHERE id = ?");
            $stmt->bind_param("ssi", $_POST['nome'], $_POST['cargo'], $_POST['id']);
            $stmt->execute();
            $_SESSION['success'] = "Membro atualizado!";
        }
        
        elseif (isset($_POST['delete_eqpesporte'])) {
            $stmt = $conn->prepare("DELETE FROM eqpesporte WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            $_SESSION['success'] = "Membro excluído!";
        }
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro: ".$e->getMessage();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$dias_semana = [
    0 => 'Domingo',
    1 => 'Segunda',
    2 => 'Terça',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta',
    6 => 'Sábado'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Rádio Lagoa Dourada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { margin-bottom: 20px; }
        .card { margin: 10px; }
        .nav-pills .nav-link { margin: 2px; border: 1px solid #dee2e6; }
        .tab-content .tab-pane { padding: 15px; border: 1px solid #dee2e6; border-top: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin - Lagoa Dourada</a>
            <div class="navbar-nav">
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>

    <div class="container">
        <ul class="nav nav-tabs nav-justified flex-nowrap" id="myTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#programas">Programas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#equipelagoa">Equipe Lagoa</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#eqpesporte">Equipe Esporte</a></li>
        </ul>

        <div class="tab-content">
            <!-- Programas -->
            <div class="tab-pane active" id="programas">
                <h3 class="mt-3">Programas (Grupo RdsLag)</h3>
                
                <ul class="nav nav-pills mb-3">
                    <?php foreach ($dias_semana as $num => $dia): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $num == date('w') ? 'active' : '' ?>" 
                           data-bs-toggle="pill" 
                           href="#dia-<?= $num ?>">
                           <?= $dia ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content">
                    <?php foreach ($dias_semana as $num => $dia): ?>
                    <div class="tab-pane fade <?= $num == date('w') ? 'show active' : '' ?>" id="dia-<?= $num ?>">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Horário</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM programas 
                                        WHERE grupo = 'RdsLag' AND dia = '$num' 
                                        ORDER BY inicio";
                                $result = $conn->query($sql);
                                
                                if ($result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= $row['nome'] ?></td>
                                    <td><?= date("H:i", strtotime($row['inicio'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editProgramaModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-nome="<?= $row['nome'] ?>"
                                            data-inicio="<?= date('H:i', strtotime($row['inicio'])) ?>"
                                            data-dia="<?= $row['dia'] ?>">
                                            Editar
                                        </button>
                                        
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete_programa" class="btn btn-sm btn-danger">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Nenhum programa cadastrado neste dia</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addProgramaModal">
                    Adicionar Programa
                </button>
            </div>

            <!-- Equipe Lagoa -->
            <div class="tab-pane" id="equipelagoa">
                <h3 class="mt-3">Equipe Lagoa</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM equipelagoa";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['nome'] ?></td>
                            <td><?= $row['cargo'] ?></td>
                            <td>
                            <button type="button" class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editEquipeLagoaModal"
                                data-id="<?= $row['id'] ?>"
                                data-nome="<?= $row['nome'] ?>"
                                data-cargo="<?= $row['cargo'] ?>">
                                Editar
                            </button>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_equipelagoa" class="btn btn-sm btn-danger">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEquipeLagoaModal">
                    Adicionar Membro
                </button>
            </div>

            <!-- Equipe Esporte -->
            <div class="tab-pane" id="eqpesporte">
                <h3 class="mt-3">Equipe Esporte</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM eqpesporte";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['nome'] ?></td>
                            <td><?= $row['cargo'] ?></td>
                            <td>
                            <button type="button" class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editEquipeEsporteModal"
                                data-id="<?= $row['id'] ?>"
                                data-nome="<?= $row['nome'] ?>"
                                data-cargo="<?= $row['cargo'] ?>">
                                Editar
                            </button>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_eqpesporte" class="btn btn-sm btn-danger">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEquipeEsporteModal">
                    Adicionar Membro
                </button>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php include 'modals.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Configuração para Programas
    document.getElementById('editProgramaModal')?.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.querySelector('#editProgramaModal input[name="id"]').value = button.dataset.id;
        document.querySelector('#editProgramaModal input[name="nome"]').value = button.dataset.nome;
        document.querySelector('#editProgramaModal input[name="inicio"]').value = button.dataset.inicio;
        document.querySelector('#editProgramaModal select[name="dia"]').value = button.dataset.dia;
    });

    // Configuração para Equipe Lagoa
    document.getElementById('editEquipeLagoaModal')?.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.querySelector('#editEquipeLagoaModal input[name="id"]').value = button.dataset.id;
        document.querySelector('#editEquipeLagoaModal input[name="nome"]').value = button.dataset.nome;
        document.querySelector('#editEquipeLagoaModal input[name="cargo"]').value = button.dataset.cargo;
    });

    // Configuração para Equipe Esporte
    document.getElementById('editEquipeEsporteModal')?.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.querySelector('#editEquipeEsporteModal input[name="id"]').value = button.dataset.id;
        document.querySelector('#editEquipeEsporteModal input[name="nome"]').value = button.dataset.nome;
        document.querySelector('#editEquipeEsporteModal input[name="cargo"]').value = button.dataset.cargo;
    });
    </script>
</body>
</html>
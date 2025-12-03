<?php
require_once 'conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (!empty($email) && !empty($senha)) {
        $sql = "SELECT id, nome, tipo FROM usuarios WHERE email = ? AND senha = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ss", $email, $senha);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo'];
                header("Location: index.php");
                exit(); // Adicione exit() após o header
            } else {
                $erro = "E-mail ou senha incorretos!";
            }
        } else {
            $erro = "Erro na preparação da consulta: " . $conn->error;
        }
    } else {
        $erro = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Admin Rádio</title>
    <style>
        body { background: #f0f0f0; font-family: Arial; }
        .login-box { width: 300px; margin: 100px auto; padding: 20px; background: white; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #007bff; color: white; border: none; padding: 10px; width: 100%; }
        .erro { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Administrativo</h2>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
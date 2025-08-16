<?php
// login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';
require_once 'includes/functions.php';

// Redireciona se já estiver logado
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = display_error('Preencha todos os campos');
    } else {
        $sql = "SELECT id, username, password FROM usuarios WHERE username = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: index.php");
                    exit;
                }
            }
            
            // Mensagem genérica por segurança
            $error = display_error('Usuário ou senha incorretos');
            $stmt->close();
        } else {
            $error = display_error('Erro no sistema. Tente novamente.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Login</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Acesso Restrito</h2>
                        
                        <?= $error ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                         <a href="recuperar_senha.php" class="login-link-recuperar">Esqueceu sua senha?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
     <?php include 'includes/footer.php'; ?>
</footer>
</html>
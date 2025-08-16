<?php
// Arquivo: cardapio_admin/resetar_senha.php
include 'config/database.php';

$message = '';
$token = $_GET['token'] ?? '';
$user_id = null; // Para armazenar o ID do usuário após a validação do token

// 1. Validar o token
if (empty($token)) {
    $message = '<div class="alert alert-danger" role="alert">Token de recuperação inválido ou não fornecido.</div>';
} else {
    $sql = "SELECT id, reset_token_expires_at FROM usuarios WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verificar se o token expirou
        $expires_at = new DateTime($user['reset_token_expires_at']);
        $now = new DateTime();

        if ($now > $expires_at) {
            $message = '<div class="alert alert-danger" role="alert">O token de recuperação expirou. Por favor, solicite um novo.</div>';
            // Opcional: Limpar o token expirado do banco
            $clear_token_sql = "UPDATE usuarios SET reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?";
            $clear_token_stmt = $conn->prepare($clear_token_sql);
            $clear_token_stmt->bind_param("i", $user['id']);
            $clear_token_stmt->execute();
            $clear_token_stmt->close();
        } else {
            $user_id = $user['id']; // Token válido, pode prosseguir
        }
    } else {
        $message = '<div class="alert alert-danger" role="alert">Token de recuperação inválido ou já utilizado.</div>';
    }
    $stmt->close();
}

// 2. Processar a nova senha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = '<div class="alert alert-danger" role="alert">As senhas não coincidem.</div>';
    } elseif (strlen($new_password) < 6) { // Exemplo de validação de senha
        $message = '<div class="alert alert-danger" role="alert">A nova senha deve ter pelo menos 6 caracteres.</div>';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Atualizar a senha e invalidar o token
        $update_sql = "UPDATE usuarios SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $user_id);

        if ($update_stmt->execute()) {
            $message = '<div class="alert alert-success" role="alert">Senha redefinida com sucesso! Você pode fazer login agora.</div>';
            $user_id = null; // Invalida o formulário após sucesso para evitar reenvio
        } else {
            $message = '<div class="alert alert-danger" role="alert">Erro ao redefinir a senha.</div>';
        }
        $update_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container-resetar {
            max-width: 500px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-resetar">
        <h2 class="text-center mb-4">Redefinir Senha</h2>
        <?php echo $message; ?>

        <?php if ($user_id): // Só mostra o formulário se o token for válido ?>
        <form action="resetar_senha.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nova Senha:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
        </form>
        <?php else: // Mensagem para token inválido/expirado, sem formulário ?>
        <p class="text-center mt-3"><a href="login.php">Voltar para o Login</a></p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>

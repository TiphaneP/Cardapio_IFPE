<?php
// Arquivo: cardapio_admin/recuperar_senha.php
include 'config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    $sql = "SELECT id FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Gerar um token único e seguro
        $token = bin2hex(random_bytes(32)); // Gera um token de 64 caracteres hexadecimais
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora

        // Armazenar o token no banco de dados
        $update_sql = "UPDATE usuarios SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $token, $expires_at, $user_id);

        if ($update_stmt->execute()) {
            // SIMULANDO ENVIO DE E-MAIL: Exibir o link para teste local
            $reset_link = "http://localhost/cardapio_admin/resetar_senha.php?token=" . $token;
            $message = '<div class="alert alert-success" role="alert">
                            Um link de recuperação de senha foi gerado. <br>
                            <p><strong>Em um ambiente real, este link seria enviado para o e-mail do usuário.</strong></p>
                            <p>Para teste, clique no link: <a href="' . htmlspecialchars($reset_link) . '" class="link-quebra-palavra">' . htmlspecialchars($reset_link) . '</a></p>
                            <small>Este link é válido por 1 hora.</small>
                        </div>';
        } else {
            $message = '<div class="alert alert-danger" role="alert">Erro ao gerar token de recuperação.</div>';
        }
        $update_stmt->close();
    } else {
        $message = '<div class="alert alert-warning" role="alert">Usuário não encontrado.</div>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
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
        .container-recuperar {
            max-width: 500px;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }

         .link-quebra-palavra {
            word-wrap: break-word; /* Quebra palavras longas se necessário */
            word-break: break-all; /* Quebra em qualquer caractere se word-wrap não for suficiente */
            overflow-wrap: break-word; /* Propriedade mais recente para quebra de palavra */
        }
    </style>
</head>
<body>
    <div class="container-recuperar">
        <h2 class="text-center mb-4">Recuperar Senha</h2>
        <?php echo $message; ?>
        <form action="recuperar_senha.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Solicitar Recuperação</button>
            <p class="text-center mt-3"><a href="login.php">Voltar para o Login</a></p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>

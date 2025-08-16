<?php
require_once 'includes/header.php';
require_login();

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = (int)$_GET['id'];
$error = '';
$item = null;

// Carrega o item
$sql = "SELECT * FROM cardapio_semanal WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('index.php');
}

$item = $result->fetch_assoc();

// Processa a edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'dia_semana' => sanitize($_POST['dia_semana']),
        'turno' => sanitize($_POST['turno']),
        'tipo_prato' => sanitize($_POST['tipo_prato']),
        'descricao' => sanitize($_POST['descricao']),
        'observacoes' => sanitize($_POST['observacoes']),
        'status' => sanitize($_POST['status']),
        'id' => $id
    ];

    $sql = "UPDATE cardapio_semanal SET 
            dia_semana = ?, 
            turno = ?, 
            tipo_prato = ?, 
            descricao = ?, 
            observacoes = ?, 
            status = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", 
        $dados['dia_semana'],
        $dados['turno'],
        $dados['tipo_prato'],
        $dados['descricao'],
        $dados['observacoes'],
        $dados['status'],
        $dados['id']
    );

    if ($stmt->execute()) {
        $success = display_success('Item atualizado com sucesso!');
        // Recarrega os dados atualizados
        $item = $dados;
    } else {
        $error = display_error('Erro ao atualizar: ' . $stmt->error);
    }
}
?>

<h1 class="mb-4">Editar Item</h1>

<?= $error ?? $success ?? '' ?>

<form method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="dia_semana" class="form-label">Dia da Semana</label>
            <select class="form-select" id="dia_semana" name="dia_semana" required>
                <?php 
                $dias = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
                foreach ($dias as $dia): ?>
                    <option value="<?= $dia ?>" <?= $item['dia_semana'] === $dia ? 'selected' : '' ?>>
                        <?= $dia ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="turno" class="form-label">Turno</label>
            <select class="form-select" id="turno" name="turno" required>
                <option value="Café da Manhã" <?= $item['turno'] === 'Café da Manhã' ? 'selected' : '' ?>>Café da Manhã</option>
                <option value="Almoço" <?= $item['turno'] === 'Almoço' ? 'selected' : '' ?>>Almoço</option>
                <option value="Jantar" <?= $item['turno'] === 'Jantar' ? 'selected' : '' ?>>Jantar</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="tipo_prato" class="form-label">Tipo de Prato</label>
            <input type="text" class="form-control" id="tipo_prato" name="tipo_prato" 
                   value="<?= $item['tipo_prato'] ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="ativo" <?= $item['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= $item['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>
        
        <div class="col-12">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= $item['descricao'] ?></textarea>
        </div>
        
        <div class="col-12">
            <label for="observacoes" class="form-label">Observações</label>
            <textarea class="form-control" id="observacoes" name="observacoes" rows="2"><?= $item['observacoes'] ?></textarea>
        </div>
        
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
<?php
require_once 'includes/header.php';
require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'dia_semana' => sanitize($_POST['dia_semana']),
        'turno' => sanitize($_POST['turno']),
        'tipo_prato' => sanitize($_POST['tipo_prato']),
        'descricao' => sanitize($_POST['descricao']),
        'observacoes' => sanitize($_POST['observacoes']),
        'status' => sanitize($_POST['status'])
    ];

    $sql = "INSERT INTO cardapio_semanal (dia_semana, turno, tipo_prato, descricao, observacoes, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", 
        $dados['dia_semana'],
        $dados['turno'],
        $dados['tipo_prato'],
        $dados['descricao'],
        $dados['observacoes'],
        $dados['status']
    );

    if ($stmt->execute()) {
        $success = display_success('Item adicionado com sucesso!');
    } else {
        $error = display_error('Erro ao adicionar: ' . $stmt->error);
    }
}
?>

<h1 class="mb-4">Adicionar Item</h1>

<?= $error ?? $success ?? '' ?>

<form method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="dia_semana" class="form-label">Dia da Semana</label>
            <select class="form-select" id="dia_semana" name="dia_semana" required>
                <option value="">Selecione...</option>
                <option value="Segunda-feira">Segunda-feira</option>
                <option value="Terça-feira">Terça-feira</option>
                <option value="Quarta-feira">Quarta-feira</option>
                <option value="Quinta-feira">Quinta-feira</option>
                <option value="Sexta-feira">Sexta-feira</option>
                <option value="Sábado">Sábado</option>
                <option value="Domingo">Domingo</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="turno" class="form-label">Turno</label>
            <select class="form-select" id="turno" name="turno" required>
                <option value="">Selecione...</option>
                  <option value="Café da Manhã">Café da Manhã</option>
                <option value="Almoço">Almoço</option>
                <option value="Jantar">Jantar</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="tipo_prato" class="form-label">Tipo de Prato</label>
            <input type="text" class="form-control" id="tipo_prato" name="tipo_prato" required>
        </div>
        
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>
        
        <div class="col-12">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
        </div>
        
        <div class="col-12">
            <label for="observacoes" class="form-label">Observações</label>
            <textarea class="form-control" id="observacoes" name="observacoes" rows="2"></textarea>
        </div>
        
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
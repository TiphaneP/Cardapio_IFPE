<?php
require_once 'includes/header.php';
require_login();

// Consulta os itens agrupados por dia e turno
$sql = "SELECT * FROM cardapio_semanal 
        ORDER BY FIELD(dia_semana, 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'),
        FIELD(turno, 'Café da Manhã', 'Almoço', 'Jantar'),
        tipo_prato";
$result = $conn->query($sql);

// Data da última atualização
$ultima_query = $conn->query("SELECT MAX(created_at) as ultima FROM cardapio_semanal");
$ultima_atualizacao = $ultima_query->fetch_assoc();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success">Cardápio Semanal</h1>
        <a href="adicionar.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Adicionar Item
        </a>
    </div>

    <?php if ($ultima_atualizacao && $ultima_atualizacao['ultima']): ?>
    <div class="alert alert-light mb-4">
        <i class="bi bi-clock-history"></i> Última atualização: <?= date('d/m/Y H:i', strtotime($ultima_atualizacao['ultima'])) ?>
    </div>
    <?php endif; ?>

    <?php
    $current_day = '';
    $current_turno = '';
    
    if ($result && $result->num_rows > 0):
        while ($item = $result->fetch_assoc()):
            // Novo dia
            if ($current_day != $item['dia_semana']):
                if ($current_day != ''): ?>
                    </div> </div> </div> <?php endif;
                
                $current_day = $item['dia_semana'];
                $current_turno = ''; // Reseta o turno ao mudar o dia
        ?>
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0"><?= htmlspecialchars($item['dia_semana']) ?></h3>
                </div>
                <div class="card-body">
        <?php endif;
        
        // Novo turno
        if ($current_turno != $item['turno']):
            if ($current_turno != ''): ?>
                    </div> <?php endif;
            
            $current_turno = $item['turno'];
            $badge_class = match($item['turno']) {
                'Café da Manhã' => 'bg-warning text-dark',
                'Almoço' => 'bg-success',
                'Jantar' => 'bg-primary',
                default => 'bg-secondary'
            };
        ?>
            <div class="mb-4">
                <h4 class="mb-3">
                    <span class="badge <?= $badge_class ?>">
                        <?= htmlspecialchars($item['turno']) ?>
                    </span>
                </h4>
                
                <div class="refeicoes-group">
        <?php endif; ?>
        
                    <div class="refeicao-item mb-3 p-3 border rounded">
                        <div class="d-flex align-items-start">
                            <strong class="me-2"><?= htmlspecialchars($item['tipo_prato']) ?>:</strong>
                            <div class="flex-grow-1">
                                <div><?= nl2br(htmlspecialchars($item['descricao'])) ?></div>
                                <?php if (!empty($item['observacoes'])): ?>
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-info-circle"></i> 
                                        <?= nl2br(htmlspecialchars($item['observacoes'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-2">
                            <span class="badge bg-<?= $item['status'] == 'ativo' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($item['status']) ?>
                            </span>
                            <div class="btn-actions">
                                <a href="editar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="excluir.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                    <i class="bi bi-trash"></i> Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php 
        endwhile;
        ?>
                </div> </div> </div> <?php else: ?>
        <div class="alert alert-info">
            Nenhum item cadastrado ainda. <a href="adicionar.php" class="alert-link">Clique aqui</a> para adicionar o primeiro item.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
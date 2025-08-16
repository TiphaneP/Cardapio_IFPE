<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__.'/../config/database.php';

$response = [
    'cardapio' => [],
    'last_updated' => null // Inicializa como nulo
];

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Erro de conexão: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM cardapio_semanal WHERE status = 'ativo' 
            ORDER BY FIELD(dia_semana, 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 
            'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'),
            FIELD(turno, 'Café da Manhã', 'Almoço', 'Jantar')";

    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
     $response['cardapio'] = $data;
     
    // --- 2. Busca a última data de atualização ---
    // Usamos data_atualizacao, pois é a coluna que é atualizada em alterações
    $sql_last_updated = "SELECT MAX(data_atualizacao) as ultima_atualizacao FROM cardapio_semanal";
    $result_last_updated = $conn->query($sql_last_updated);

    if ($result_last_updated && $row_last_updated = $result_last_updated->fetch_assoc()) {
        if ($row_last_updated['ultima_atualizacao']) {
            // Cria um objeto DateTime para formatar corretamente
            $dt = new DateTime($row_last_updated['ultima_atualizacao']);
            $response['last_updated'] = $dt->format('d/m/Y H:i'); // Formata para exibir HH:MM
        }
    }

    echo json_encode($response);

    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}

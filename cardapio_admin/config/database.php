<?php
// config/database.php

// Dados de conexão
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cardapio_if');

// Conexão com tratamento de erros
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Mensagem detalhada para desenvolvimento
    die("ERRO NO BANCO DE DADOS: " . $e->getMessage() . 
        "<br>Verifique:<br>" .
        "- Servidor MySQL está rodando?<br>" .
        "- Banco de dados '" . DB_NAME . "' existe?<br>" .
        "- Credenciais estão corretas?");
}
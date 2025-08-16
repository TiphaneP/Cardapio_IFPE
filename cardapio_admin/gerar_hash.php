<?php
// Arquivo temporário: cardapio_admin/gerar_hash.php
$senha_em_texto_puro = 'minhasenhaforte123'; // <<< COLOQUE A SENHA QUE VOCÊ QUER USAR PARA O SEU NOVO USUÁRIO
$hash_da_senha = password_hash($senha_em_texto_puro, PASSWORD_DEFAULT);

echo "O hash para a senha '{$senha_em_texto_puro}' é:<br>";
echo "<strong>" . htmlspecialchars($hash_da_senha) . "</strong>";
echo "<br><br>Copie este hash e cole no comando SQL abaixo.";
?>
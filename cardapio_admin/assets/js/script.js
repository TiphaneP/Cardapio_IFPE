// Funções JavaScript básicas
document.addEventListener('DOMContentLoaded', function() {
    // Confirmação para ações importantes
    document.querySelectorAll('.confirm-action').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja realizar esta ação?')) {
                e.preventDefault();
            }
        });
    });
    
    console.log('Sistema de Cardápio carregado!');
});
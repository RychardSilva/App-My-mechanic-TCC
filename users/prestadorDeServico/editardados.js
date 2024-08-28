document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit-idServico').value = this.getAttribute('data-id');
            document.getElementById('edit-nomeServico').value = this.getAttribute('data-nome');
            document.getElementById('edit-descricao').value = this.getAttribute('data-descricao');
            document.getElementById('edit-form-container').style.display = 'block';
        });
    });

    document.getElementById('cancel-edit').addEventListener('click', function() {
        document.getElementById('edit-form-container').style.display = 'none';
    });

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const idServico = this.getAttribute('data-id');
            if (confirm('Deseja mesmo excluir esse serviço?')) {
                document.getElementById('delete-idServico').value = idServico;
                document.getElementById('delete-form').submit();
            }
        });
    });
});
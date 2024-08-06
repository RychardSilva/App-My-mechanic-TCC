document.getElementById('tipoUsuario').addEventListener('change', function() {
    var pessoaFisicaFields = document.getElementById('pessoaFisicaFields');
    var pessoaJuridicaFields = document.getElementById('pessoaJuridicaFields');
    var prestadorDeServicoFields = document.getElementById('prestadorDeServicoFields');

    // Esconder todos os campos adicionais
    pessoaFisicaFields.style.display = 'none';
    pessoaJuridicaFields.style.display = 'none';
    prestadorDeServicoFields.style.display = 'none';

    // Mostrar campos adicionais conforme o tipo de usuário selecionado
    if (this.value === 'PessoaFisica') {
        pessoaFisicaFields.style.display = 'block';
    } else if (this.value === 'PessoaJuridica') {
        pessoaJuridicaFields.style.display = 'block';
    } else if (this.value === 'PrestadorDeServico') {
        prestadorDeServicoFields.style.display = 'block';
    }

    // Log para depuração
    console.log('Tipo de usuário selecionado:', this.value);
});
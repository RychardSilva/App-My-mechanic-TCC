document.addEventListener('DOMContentLoaded', function() {
    var tipoUsuario = document.getElementById('tipoUsuario');
    var pessoaFisicaFields = document.getElementById('pessoaFisicaFields');
    var pessoaJuridicaFields = document.getElementById('pessoaJuridicaFields');
    var prestadorDeServicoFields = document.getElementById('prestadorDeServicoFields');

    function updateFields() {
        pessoaFisicaFields.style.display = 'none';
        pessoaJuridicaFields.style.display = 'none';
        prestadorDeServicoFields.style.display = 'none';

        if (tipoUsuario.value === 'PessoaFisica') {
            pessoaFisicaFields.style.display = 'block';
        } else if (tipoUsuario.value === 'PessoaJuridica') {
            pessoaJuridicaFields.style.display = 'block';
        } else if (tipoUsuario.value === 'PrestadorDeServico') {
            prestadorDeServicoFields.style.display = 'block';
        }
    }

    // Chama a função quando a página é carregada
    updateFields();

    // Chama a função quando o tipo de usuário é alterado
    tipoUsuario.addEventListener('change', updateFields);

    // Log para depuração
    console.log('Tipo de usuário inicial:', tipoUsuario.value);
});
document.addEventListener('DOMContentLoaded', function() {
    var tipoUsuario = document.getElementById('tipoUsuario');
    var pessoaFisicaFields = document.getElementById('pessoaFisicaFields');
    var pessoaJuridicaFields = document.getElementById('pessoaJuridicaFields');
    var prestadorDeServicoFields = document.getElementById('prestadorDeServicoFields');

    function updateFields() {
        // Esconde todos os campos adicionais
        pessoaFisicaFields.style.display = 'none';
        pessoaJuridicaFields.style.display = 'none';
        prestadorDeServicoFields.style.display = 'none';

        // Exibe o campo específico de acordo com o tipo de usuário selecionado
        if (tipoUsuario.value === 'PessoaFisica') {
            pessoaFisicaFields.style.display = 'block';
        } else if (tipoUsuario.value === 'PessoaJuridica') {
            pessoaJuridicaFields.style.display = 'block';
        } else if (tipoUsuario.value === 'PrestadorDeServico') {
            prestadorDeServicoFields.style.display = 'block';
        }
    }

    // Chama a função quando a página é carregada para garantir que o campo correto seja exibido
    updateFields();

    // Adiciona o evento de mudança para alterar os campos quando o tipo de usuário for alterado
    tipoUsuario.addEventListener('change', updateFields);

    // Log para depuração
    console.log('Tipo de usuário inicial:', tipoUsuario.value);
});

import { conectAjax } from "../ajax/conectAjax.js";

export class funcoesComuns {

    /**
     * Aplica uma máscara personalizada a um elemento de entrada numérico.
     *
     * @param {jQuery} elem – O elemento de entrada ao qual a máscara será aplicada.
     * @param {Object} metadata - Metadados que personalizam a máscara.
     * @param {string} metadata.formato - A máscara de formato desejada (padrão: '0,99' para números com duas casas decimais).
     * @param {Object} metadata.antes - Configurações para dígitos antes da vírgula decimal.
     * @param {number} metadata.antes.quantidade – O número de dígitos antes da vírgula decimal.
     * @param {Object} metadata.depois - Configurações para dígitos após a vírgula decimal.
     * @param {number} metadata.depois.quantidade – O número de dígitos após a vírgula decimal.
     * @param {boolean} metadata.reverse - Define se a máscara deve ser aplicada em modo reverso (da direita para a esquerda).
     */
    static aplicarMascaraNumero(elem, metadata = {}) {
        let format = '0,99';

        if (metadata.formato) {
            format = metadata.formato;
        }

        if ((metadata.antes && metadata.antes.quantidade) || (metadata.depois && metadata.depois.quantidade)) {

            if (metadata.antes && metadata.antes.quantidade) {
                const digitosAntes = '0'.repeat(metadata.antes.quantidade);
                format = `${digitosAntes}`;
            } else {
                format = '0'
            }

            if (metadata.depois && metadata.depois.quantidade) {
                const digitosDepois = '9'.repeat(metadata.depois.quantidade);
                if (digitosDepois) {
                    format += `,${digitosDepois}`;
                }
            }

        }

        elem.mask(format, { reverse: metadata.reverse });

    }

    static configurarCampoSelect2(elem, urlApi, options = {}) {
        const {
            minimo = 3, placeholder = 'Selecione uma opção'
        } = options;

        elem.select2({
            language: {
                inputTooShort: function (args) {
                    var caracteres = args.minimum - args.input.length;
                    return `Digite ${caracteres} ou mais caracteres`;
                },
                noResults: function () {
                    return 'Nenhum resultado encontrado';
                },
                searching: function () {
                    return 'Pesquisando...';
                }
            },
            ajax: {
                dataType: 'json',
                delay: 250,
                transport: function (params, success) {
                    var texto = params.data.term; // Captura o valor do texto

                    // Adiciona o valor do texto ao corpo da solicitação
                    var ajaxOptions = {
                        url: urlApi,
                        type: 'POST',
                        data: { 'texto': texto },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: success,
                        error: function (xhr, textStatus, errorThrown) {
                            const objAjax = new conectAjax('');
                            const erro = objAjax.tratamentoErro(xhr);
                            $.notify(erro.message, 'error');
                        }
                    };
                    return $.ajax(ajaxOptions);
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                },
                cache: true
            },
            placeholder: placeholder,
            allowClear: true,
            minimumInputLength: minimo

        });

    }

    static retornaDigitoMatricula(matricula) {
        matricula = String(matricula);

        let mult = 2;
        let soma = 0;
        let s = "";

        for (let i = matricula.length - 1; i >= 0; i--) {
            s = (mult * parseInt(matricula[i], 10)) + s;
            if (--mult < 1) {
                mult = 2;
            }
        }

        for (let i = 0; i < s.length; i++) {
            soma = soma + parseInt(s[i], 10);
        }

        soma = soma % 10;

        if (soma !== 0) {
            soma = 10 - soma;
        }

        return parseInt(soma, 10);
    }

    static eventoEsconderExibir(elem, botao) {
        botao.click(function () {
            elem.toggle();
        });
    }

}
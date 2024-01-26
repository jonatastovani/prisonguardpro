import { conectAjax } from "../ajax/conectAjax.js";
import { enumAction } from "./enumAction.js";

export class funcoesComuns {

    /**
     * Remove todos os caracteres não numéricos de uma string.
     * @param {string} num – A string a ser processada.
     * @returns {string} – A string com apenas caracteres numéricos.
     */
    static retornaSomenteNumeros(num) {
        return String(num).replace(/\D/g, '');
    }
    
    /**
     * Gera um formulário e redireciona o usuário para a URL especificada.
     *
     * @param {string} redirecionamento - O URL para redirecionar após o envio do formulário.
     * @param {Object[]} arrInputs – Um array contendo dados de entrada para o formulário.
     * @param {Object} options - Opções adicionais para configurar atributos do formulário e botão de envio.
     * @param {Object} options.formAttr – Atributos do formulário.
     * @param {string} options.formAttr.method - O atributo do método para o formulário (padrão: 'POST').
     * @param {boolean} options.formAttr.hidden - Especifica se o formulário deve ser oculto (padrão: true).
     * @param {string} options.formAttr.id – O atributo ID do formulário.
     * @param {string} options.formAttr.class – O atributo de classe CSS para o formulário.
     * @param {Object} options.submit – Atributos para o botão enviar.
     * @param {string} options.submit.name - O atributo de nome para o botão de envio (padrão: 'submit').
     * @param {boolean} options.submit.hidden - Especifica se o botão de envio deve ser oculto (padrão: false).
     * @param {string} options.submit.value - O atributo de valor para o botão de envio (padrão: 'Enviar').
     * @param {string} options.submit.id – O atributo de ID do botão de envio.
     * @param {string} options.submit.class – O atributo de classe CSS para o botão de envio.
     * @param {string} options.returnElem - Determina o elemento a ser retornado ('form' para o formulário inteiro, 'submit' para o botão de envio).
     * @returns {HTMLFormElement|HTMLInputElement} - O elemento do formulário ou botão de envio com base na opção de retorno especificada.
     */
    static formularioRedirecionamento(redirecionamento, arrInputs, options = {}) {
        const { formAttr = { method: 'POST', hidden: true, id: '', class: '' },
            submit = { name: 'submit', hidden: false, value: 'Enviar', id: '', class: '' },
            returnElem = 'submit' } = options;

        let form = document.createElement('form');
        form.id = formAttr.id;
        form.hidden = formAttr.hidden;
        form.method = formAttr.method;
        form.action = redirecionamento;

        arrInputs.forEach(input => {

            let newInput = document.createElement('input');
            newInput.type = input.type ? input.type : 'hidden';
            newInput.name = input.name;
            if(Array.isArray(input.value)){
                newInput.value = JSON.stringify(input.value);
            } else {
                newInput.value = input.value;
            }
            form.appendChild(newInput);

        });

        let submitButton = document.createElement('input');
        submitButton.type = 'submit';
        submitButton.id = submit.id;
        submitButton.className = submit.class;
        submitButton.name = submit.name;
        submitButton.hidden = submit.hidden;
        submitButton.value = submit.value;
        form.appendChild(submitButton);
        document.body.appendChild(form);

        let returnElement = submitButton;

        switch (returnElem) {
            case 'form':
                returnElement = form;
                break;

            default:
                returnElement = submitButton
        }

        return returnElement;
    }

    /**
     * Gera um objeto de filtro de data com base nos valores início e fim.
     *
     * @param {string} valorInicio - A data de início do filtro (no formato AAAA-MM-DD).
     * @param {string} valorFim - A data de fim do filtro (no formato AAAA-MM-DD).
     * @returns {Object} – O objeto de filtro de data.
     */
    static gerarFiltroData(valorInicio, valorFim) {
        let filtro = {};

        if (valorInicio) {
            filtro.inicio = valorInicio;
        }

        if (valorFim) {
            filtro.fim = valorFim;
        }

        return filtro;
    }

    /**
     * Gets an array of default values considered as invalid for generating filters.
     *
     * @returns {Array} - An array of default values.
     */
    static buscarValoresInvalidosGerarFiltros() {
        return ['undefined', undefined, 'null', null, '0', 0, ''];
    }

    /**
     * Fills a select element with options retrieved from an API.
     *
     * @param {jQuery} elem - O elemento de seleção encapsulado em jQuery a ser preenchido.
     * @param {string} urlApi – A URL da API da qual buscar dados.
     * @param {Object} opcoes - Opções adicionais para personalizar o processo de preenchimento.
     * @param {boolean} options.inserirPrimeiraOpcao - Se deseja inserir a primeira opção (padrão: true).
     * @param {string} options.primeiraOpcaoNome - O nome da primeira opção (padrão: 'Selecione').
     * @param {string} options.primeiraOpcaoValor - O valor da primeira opção (padrão: '').
     * @param {string} options.idOpcaoSelecionada - O ID da opção a ser marcada como selecionada (padrão: o valor atual do elemento select).
     * @param {string} options.campoExibir - O nome da coluna a ser exibida nas opções (padrão: 'nome').
     * @param {string} options.tipoRequest - O tipo de solicitação (por exemplo, "GET" ou "POST").
     * @returns {Promise} - Uma promessa que é resolvida quando o elemento selecionado é preenchido ou rejeitado por erro.
     */
    static async preencherSelect(elem, urlApi, opcoes = {}) {
        const {
            inserirPrimeiraOpcao: inserirPrimeiraOpcao = true,
            primeiraOpcaoNome: primeiraOpcaoNome = 'Selecione',
            primeiraOpcaoValor: primeiraOpcaoValor = '',
            idOpcaoSelecionada: idOpcaoSelecionada = elem.val(),
            campoExibir: campoExibir = 'nome',
            tipoRequest: tipoRequest = enumAction.GET,
            envData: envData = {},
        } = opcoes;

        const obj = new conectAjax(urlApi);

        try {
            let response;

            if (tipoRequest === enumAction.GET) {
                response = await obj.getRequest();
            } else if (tipoRequest === enumAction.POST) {
                obj.setAction(tipoRequest);
                obj.setData(envData);
                response = await obj.envRequest();
            } else {
                throw new Error('Tipo de solicitação inválido. Use "GET", "POST" ou "PUT".');
            }

            let strOptions = '';

            if (inserirPrimeiraOpcao) {
                strOptions += `<option value="${primeiraOpcaoValor}">${primeiraOpcaoNome}</option>`;
            }

            response.data.forEach(result => {
                const id = result.id;
                const valor = result[campoExibir];
                const strSelected = (id == idOpcaoSelecionada ? ' selected' : '');
                strOptions += `\n<option value="${id}"${strSelected}>${valor}</option>`;
            });

            elem.html(strOptions);
            return Promise.resolve('A lista foi carregada com sucesso!');
        } catch (error) {
            const errorMessage = 'Erro ao preencher';
            console.error(error);
            elem.html(`<option>${errorMessage}</option>`);
            $.notify(error.message)
            return Promise.reject(error);
        }
    }

    /**
     * Obtém os valores dos elementos de um formulário e retorna um objeto ou string formatada.
     *
     * @param {string} container - Container a ser processado.
     * @param {number} tipoRetorno - O tipo de retorno desejado: 1 para objeto, 2 para string.
     * @param {boolean} blnDisabled – Indica se os elementos desabilitados devem ser incluídos (true) ou excluídos (false).
     * @param {boolean} keyId - Define se o atributo "name" (keyId=false) ou o atributo "id" (keyId=true) deve ser usado como chave no objeto de retorno.
     *
     * @returns {object|string} – Um objeto com os valores dos elementos do formulário (tipoRetorno 1)
     * ou uma string formatada com os valores do elemento (tipoRetorno 2).
     */
    static obterValoresDosInputs(container, tipoRetorno = 1, blnDisabled = false, keyId = false) {

        const formData = {};
        let strReturn = '';

        // const elemInput = form.elements;
        const elemInput = $(container).find('input, select, textarea');

        for (let i = 0; i < elemInput.length; i++) {
            const element = elemInput[i];
            const id = element.id;
            const name = element.name;
            const val = element.value;
            const disabled = element.disabled;

            if (blnDisabled || !blnDisabled && !disabled) {
                if ((id && keyId)) {
                    formData[id] = val.trim();
                    strReturn += `${id}:${val.trim()}\n`;
                } else if ((name && !keyId)) {
                    formData[name] = val.trim();
                    strReturn += `${name}:${val.trim()}\n`;
                }
            }
        }

        switch (tipoRetorno) {
            case 1:
                return formData;

            case 2:
                return strReturn;
        }

    }

    static simulacaoCarregando(elem, status = true) {

        if (status) {
            $(elem).addClass('disabled').attr('disabled', true);
            $(elem).find('.spinner-border').removeClass('d-none');
        } else {
            $(elem).removeClass('disabled').removeAttr('disabled');
            $(elem).find('.spinner-border').addClass('d-none');
        }

    }
    
    static addEventToggleDiv(dataSearchDiv, toggleButton, options = {}) {
        const { self = null, minWidht = 991 } = options;

        function toggleDataSearch() {
            const screenWidth = $(window).width();

            if (screenWidth <= minWidht) {
                dataSearchDiv.hide();
                toggleButton.show();
            } else {
                dataSearchDiv.show();
                toggleButton.hide();
            }

            if (self != null) {
                if (typeof self.adjustTableHeight === 'function') {
                    self.adjustTableHeight();
                }
            }
        }

        toggleButton.click(function (event) {
            event.preventDefault();
            dataSearchDiv.toggle();
        });

        $(window).on('resize.toggleDataSearch', function () {
            toggleDataSearch();
        });

        $(':input').on('focus', function () {
            $(window).off('resize.toggleDataSearch');
        });

        $(':input').on('blur', function () {
            $(window).on('resize.toggleDataSearch', function () {
                toggleDataSearch();
            });
        });

        toggleDataSearch();
    }

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
    
    static eventoEsconderExibir(elem, botao) {
        botao.click(function () {
            elem.toggle();
        });
    }

}
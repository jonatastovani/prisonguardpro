import { conectAjax } from "../../ajax/conectAjax.js";

export class modalAlterarPresoConvivio {

    /**
     * ID do modal
     */
    #idModal;
    /** 
     * Conteúdo a ser retornado na promisse como resolve()
    */
    #retornoPromisse;
    /**
     * Variável que executará o fim do setInterval de retoro da promisse com reject()
     */
    #finalizaTimer;
    /**
     * Elemento de foco ao fechar o modal
     */
    #elemFocoFechamento;

    #idDiv;
    constructor() {
        this.#idModal = "#modalAlterarPresoConvivio";
        this.#retornoPromisse = undefined;
        this.#elemFocoFechamento = null;
        this.#finalizaTimer = false;
        this.#idDiv = undefined;
        this.addEventsButtons();
    }

    /**
     * Define o elemento de foco de fechamento.
     * @param {jQuery} elem - O elemento jQuery a ser definido como foco de fechamento.
     */
    set setFocoNoElementoAoFechar(elem) {
        this.#elemFocoFechamento = elem;
    }

    /**
     * Define o ID do container preso.
     * @param {String} id - O ID do container preso.
     */
    set setIdDiv(id) {
        this.#idDiv = id;
    }

    modalAbrir() {

        const self = this;
        if (self.#idDiv) {

            self.getDataAll();
            $(self.#idModal).modal('show');

            return new Promise(function (resolve) {

                const checkConfirmacao = setInterval(function () {

                    if (self.#retornoPromisse !== undefined || self.#finalizaTimer) {

                        clearInterval(checkConfirmacao);
                        if (self.#retornoPromisse !== undefined) {
                            resolve(self.#retornoPromisse);
                        }

                        self.#retornoPromisse = undefined;
                        self.#finalizaTimer = false;
                        self.modalFechar();

                    }

                }, 100);

            });

        } else {
            const error = `O idDiv não foi informado ou não existe.\nidDiv = '${this.#idDiv}'`;
            commonFunctions.generateNotification(`Não foi possível alterar o tipo de preso.\nErro: ${error}`, 'error')
            console.error(error);
        }

    }

    modalFechar() {

        const self = this;

        $(self.#idModal).modal('hide');
        self.modalLimpar();

        if (self.#elemFocoFechamento !== null && $(self.#elemFocoFechamento).length) {
            $(self.#elemFocoFechamento).focus();
            self.#elemFocoFechamento = null;
        }

    }

    modalLimpar() {

        const self = this;
        self.#idDiv = undefined;
        $(self.#idModal).find('.conviviosTipo').html('');

    }

    addEventsButtons() {

        const self = this;

        $(self.#idModal).on('click', '.btn-cancel, .btn-close', function () {
            self.#finalizaTimer = true;
        });

        $(self.#idModal).on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.#finalizaTimer = true;
                e.stopPropagation();
            }
        });

    }

    async getDataAll() {

        const self = this;
        const obj = new conectAjax(`${urlPresoConvivio}/tipos`);
        const conviviosTipo = $(self.#idModal).find('.conviviosTipo');

        try {
            const response = await obj.getRequest();
            if (response.data.length) {
                response.data.forEach(tipo => {

                    let strPadrao = tipo.convivio_padrao_bln ? ' (Padrão)' : '';
                    let strCor = '';
                    if (tipo.cor) {
                        strCor = `style="color: ${tipo.cor.cor_texto}; background-color: ${tipo.cor.cor_fundo};"`
                    }

                    conviviosTipo.append(`
                    <div class="row mt-2">
                        <button id="btnConvivoTipo${tipo.id}" class="btn" ${strCor}>
                            <h5>${tipo.nome}${strPadrao}</h5>
                            <p>${tipo.descricao}</p>
                        </button>
                    </div>`);

                    self.addQueryEvents(tipo);

                });
            }
        } catch (error) {
            console.error(error);
            const traceId = error.traceId ? error.traceId : undefined;
            commonFunctions.generateNotification(error.message, 'error', { itemsArray: error.itemsMessage, traceId: traceId });
            self.modalFechar();
        }

    }

    addQueryEvents(tipo) {

        const self = this;
        const btn = $(`#btnConvivoTipo${tipo.id}`).click(function () {
            self.#retornoPromisse = tipo;
        })
    }

}

export class alterarPresoConvivio {

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
    }

    /**
     * Define o elemento de foco de fechamento.
     * @param {jQuery} elem - O elemento jQuery a ser definido como foco de fechamento.
     */
    set setElemFocoFechamento(elem) {
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

            $(self.#idModal).modal('show');

            return new Promise(function (resolve) {

                const checkConfirmacao = setInterval(function () {

                    if (self.#retornoPromisse !== undefined || self.#finalizaTimer) {

                        clearInterval(checkConfirmacao);
                        if (self.#retornoPromisse !== undefined) {
                            resolve(self.#retornoPromisse);
                            self.modalFechar();
                        }
    
                    }
                    resolve({id:1,nome:'Preso do Seguro'});
    
                    self.#retornoPromisse = undefined;
                    self.#finalizaTimer = false;
    
                }, 100);
                
            });

        } else {
            const error = `O idDiv não foi informado ou não existe.\nidDiv = '${this.#idDiv}'`;
            $.notify(`Não foi possível alterar o tipo de preso.\nErro: ${error}`)
            console.error(error);
        }

    }

    modalFechar() {

        const self = this;

        self.#finalizaTimer = true;

        $(self.#idModal).modal('hide');
        self.#idDiv = undefined;

        if (self.#elemFocoFechamento !== null && $(self.#elemFocoFechamento).length) {
            $(self.#elemFocoFechamento).focus();
            self.#elemFocoFechamento = null;
        }

    }

    addEventosBotoes() {

        const self = this;

        $(self.#idModal).find(".btn-close").click(function () {
            self.#finalizaTimer = true;
        });

        $(self.#idModal).on('keydown', function (e) {
            if (e.key === 'Escape') {
                btnClose.click();
                e.stopPropagation();
            }
        });

    }

}
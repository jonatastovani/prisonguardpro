/**
 * ModalMessage class.
 * 
 * Initializes the modal properties and adds event listeners to its buttons.
 */
export class modalMessage {

    #idModal;
    #message;
    #confirmResult;
    #title;
    #focusElementWhenClosingModal;
    #idDefaultButton;

    constructor() {
        this.#idModal = "#modalMessage";
        this.#message = null;
        this.#confirmResult = undefined;
        this.addEvents();
        this.#title = null;
        this.#focusElementWhenClosingModal = null;

        /**
         * Specifies the default button to receive focus.
         *
         * @type {number}
         * @property {number} focusPattern - Focus pattern for buttons (1 for "Confirm" button, 2 for "Deny" button).
         */
        this.#idDefaultButton = null;
    }

    set setMessage(message) {
        this.#message = message;
    }

    set setTitle(title) {
        this.#title = title;
    }

    set setFocusElementWhenClosingModal(elem) {
        this.#focusElementWhenClosingModal = elem;
    }

    set setIdDefaultButton(id) {
        this.#idDefaultButton = id;
    }

    modalOpen() {

        const self = this;
        let title = 'Confirmação de Ação';
        if (self.#title !== null) {
            title = self.#title;
        }
        $(self.#idModal).find('.modal-title').html(title);

        if (self.#message !== null) {

            $(self.#idModal).find('.message').html(self.#message);
            $(self.#idModal).modal('show');

           setTimeout(() => {
             if (([1, 2].findIndex((item) => item == self.#idDefaultButton)) != -1) {
 
                 if (self.#idDefaultButton == 1) {
                     $(self.#idModal).find('.confirmYes').focus();
                 } else {
                     $(self.#idModal).find('.confirmNo').focus();
                 }   
 
             } else {
                 $(self.#idModal).find('.confirmNo').focus();
             }
           }, 500);

            return new Promise(function (resolve) {

                const checkConfirmation = setInterval(function () {

                    if (self.#confirmResult !== undefined) {
                        clearInterval(checkConfirmation);
                        resolve(self.#confirmResult);
                        self.modalClose();

                        self.#confirmResult = undefined;
                    }

                }, 100);

            });

        } else {
            const message = 'Nenhuma mensagem foi definida';
            console.error(message);
            $.notify(`Não foi possível abrir a confirmação. Se o problema persistir consulte o desenvolvedor.\nErro: ${message}`, 'error');
            self.modalClose();
        }

    }

    modalClose() {

        const self = this;

        self.#title = null;
        self.#message = null;

        const modal = $(self.#idModal);
        modal.modal('hide');
        modal.find("*").off();
        modal.off('keydown');

        if (self.#focusElementWhenClosingModal !== null && $(self.#focusElementWhenClosingModal).length) {
            $(self.#focusElementWhenClosingModal).focus();
            self.#focusElementWhenClosingModal = null;
        }

    }

    addEvents() {

        const self = this;

        const confirmYes = $(self.#idModal).find(".confirmYes");
        const confirmNo = $(self.#idModal).find(".confirmNo");

        confirmYes.click(function () {
            self.#confirmResult = true;
            console.log('Sim')
        });

        confirmNo.click(function () {
            self.#confirmResult = false;
            console.log('Não')
        });

        $(self.#idModal).on('keydown', function (e) {
            if (e.key === 'Escape') {
                confirmNo.click();
                e.stopPropagation();
            }
        });

    }

}

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
    #elemFocusClose;
    #idDefaultButton;

    constructor() {
        this.#idModal = "#modalMessage";
        this.#message = null;
        this.#confirmResult = undefined;
        this.addButtonsEvents();
        this.#title = null;
        this.#elemFocusClose = null;

        /**
         * Specifies the default button to receive focus.
         *
         * @type {number}
         * @property {number} focusPattern - Focus pattern for buttons (1 for "Confirm" button, 2 for "Deny" button).
         */
        this.#idDefaultButton = null;
    }

    setMessage(message) {

        this.#message = message;

    }

    setTitle(title) {

        this.#title = title;

    }

    setElemFocusClose(elem) {
        this.#elemFocusClose = elem;
    }

    setIdDefaultButton(id) {

        this.#idDefaultButton = id;

    }

    openModal() {

        const self = this;
        if (self.#title !== null) {
            $(self.#idModal).find('.title').html(self.#title);
        }

        if (self.#message !== null) {

            $(self.#idModal).find('.message').html(self.#message);
            $(self.#idModal).show();

            if (([1, 2].findIndex((item) => item == self.#idDefaultButton)) != -1) {

                if (self.#idDefaultButton == 1) {
                    $(self.#idModal).find('.confirmYes').focus();
                } else {
                    $(self.#idModal).find('.confirmNo').focus();
                }

            } else {
                $(self.#idModal).find('.confirmNo').focus();
            }

            return new Promise(function (resolve) {

                const checkConfirmation = setInterval(function () {

                    if (self.#confirmResult !== undefined) {
                        clearInterval(checkConfirmation);
                        resolve(self.#confirmResult);
                        self.closeModal();
                    }

                }, 100);

            });

        } else {

            console.error('Nenhuma mensagem foi definida');

        }

    }

    closeModal() {

        const self = this;

        this.#title = null;
        this.#confirmResult = undefined;
        this.#message = null;

        $(this.#idModal).hide();

        if (this.#elemFocusClose !== null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    addButtonsEvents() {

        const self = this;

        const confirmYes = $(self.#idModal).find(".confirmYes");
        const confirmNo = $(self.#idModal).find(".confirmNo");

        confirmYes.click(function () {
            self.#confirmResult = true;
        });

        confirmNo.click(function () {
            self.#confirmResult = false;
        });

        $(self.#idModal).on('keydown', function (e) {
            if (e.key === 'Escape') {
                confirmNo.click();
                e.stopPropagation();
            }
        });

    }

}

export class modalLoading {

    #idModal;
    #message;
    #title;
    #focusElementWhenClosingModal;

    constructor() {
        this.#idModal = "#modalLoading";
        this.#message = null;
        this.#title = null;
        this.#focusElementWhenClosingModal = null;
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

    modalOpen() {

        const self = this;
        let title = 'Carregando...';
        if (self.#title !== null) {
            title = self.#title;
        }
        $(self.#idModal).find('.modal-title').html(title);

        let message = 'Carregando...';
        if (self.#message !== null) {
            message = self.#message;
        }
        $(self.#idModal).find('.message').html(message);

        $(self.#idModal).modal('show');

    }

    modalClose() {

        const self = this;

        self.#title = null;
        self.#message = null;

        $(self.#idModal).modal('hide');

        if (self.#focusElementWhenClosingModal !== null && $(self.#focusElementWhenClosingModal).length) {
            $(self.#focusElementWhenClosingModal).focus();
            self.#focusElementWhenClosingModal = null;
        }

    }

}

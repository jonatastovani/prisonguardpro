import { bootstrapFunctions } from "./bootstrapFunctions.js";

export class systemNotifications {

    #message;
    #title;
    #type;

    constructor(message = undefined, type = 'info', render = false) {
        this.#message = message;
        this.#title = null;
        this.#type = type;

        if (render) {
            return this.render();
        }
    }

    /**
     * @param {string} message
     */
    set setMessage(message) {
        this.#message = message;
    }

    /**
     * @param {string} title
     */
    set setTitle(title) {
        this.#title = title
    }

    /**
     * @param {string} type
     */
    set typeNotification(type) {
        this.#type = type;
    }

    render() {
        const self = this;

        return new Promise(async function (resolve) {
            resolve(await bootstrapFunctions.createNotification(self.#message, { type: self.#type, title: self.#title }));
        })
    }
}
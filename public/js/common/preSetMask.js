import { commonFunctions } from "./commonFunctions.js";

export class preSetMask {

    static apply(selector, idPreSet) {

        switch (idPreSet) {
            case 1:
                preSetMask.#rgComDigito(selector)
                break

            case 2:
                preSetMask.#rgSemDigito(selector)
                break

            default:
                const message = `O ID do Preset não foi informado corretamente.`
                console.error(message)
                console.error(`ID informado = ${idPreSet}`);
                commonFunctions.generateNotification(message, 'error', { itemsArray: [`ID informado = ${idPreSet}`] });
        }

    }

    static #rgComDigito(selector) {
        $(selector).mask('#.##0-0', {
            reverse: true,
        });
    }

    static #rgSemDigito(selector) {
        $(selector).mask('#.##0', {
            reverse: true,
        });
    }
}
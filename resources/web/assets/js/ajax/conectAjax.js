import { commonFunctions } from "../commons/commonFunctions.js";
import { enumAction } from "../commons/enumAction.js";

/**
 * The `conectAjax` class provides methods for making AJAX requests to an external API and handling the responses.
 */
export class conectAjax {

    /**
     * The base URL of the API.
     * @type {string}
    */
    #urlApi;
    /**
     * The HTTP action for registering clients (e.g., 'POST', 'PUT').
     * @type {string}
    */
    #action;
    /**
     * The data to send with the request (for 'POST' or 'PUT' actions).
     * @type {string}
    */
    #data;
    /**
     * The additional parameter to include in the URL.
     * @type {string}
    */
    #param;

    /**
     * Creates a new `conectAjax` instance.
     *
     * @param {string} urlApi - The base URL of the API to make requests to.
     */
    constructor(urlApi) {
        this.#urlApi = urlApi;
        this.#action = null;
        this.#data = null;
        this.#param = null;
    }

    /**
     * Sets the HTTP action for registering clients.
     *
     * @param {string} action - The HTTP action to set (e.g., 'POST', 'PUT').
     * @returns {boolean} - Returns `true` if the action is valid, otherwise returns `false`.
     */
    setAction(action) {
        if (enumAction.isValid(action)) {
            this.#action = action;
            return true;
        } else {
            console.error('Action inválido');
            return false;
        }
    }

    /**
     * Sets the data to be sent with the request for 'POST' or 'PUT' actions.
     *
     * @param {Object} data - The data to send in the request.
     */
    setData(data) {
        this.#data = data;
    }

    /**
     * Sets an additional parameter to include in the request URL.
     *
     * @param {string} param - The additional parameter.
     */
    setParam(param) {
        this.#param = param;
    }

    /**
     * Sends a 'GET' request to the API and returns a Promise that resolves with the response data.
     *
     * @returns {Promise} - A Promise that resolves with the response data or rejects with an error message.
     */
    getData() {
        const self = this;

        let param = this.#param != null ? this.#param : '?size=10000000';
        let method = this.#action === null ? "GET" : this.#action;
        let data = this.#data !== null ? JSON.stringify(this.#data) : null;

        if (globalDebug === true) {
            console.log('URL = ', this.#urlApi + param);
            console.log('Param = ', param);
            console.log('Method = ', method);
            console.log('Data = ', data);

        }
        if (globalDebugStack === true) {
            try {
                throw new Error();
            } catch (e) {
                console.log(`Pilha = ${e.stack}`);
            }
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                url: this.#urlApi + param,
                method: method,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + self.getToken());
                },
                contentType: "application/json",
                data: data,
                dataType: "json",
                success: function (response) {

                    if (globalDebug === true) {
                        console.log('Response = ', response);
                    }

                    resolve(response);

                },
                error: function (xhr) {
                    if (globalDebug === true) {
                        console.error(xhr);
                    }

                    if (xhr.status === 401) {
                        reject({
                            status: xhr.status,
                            description: 'Este usuário não possui permissão para realizar esta ação.'
                        });
                    } else if (xhr.responseText) {
                        try {
                            const responseText = JSON.parse(xhr.responseText);
                            console.error('Erro HTTP:', xhr.status);
                            console.error(`Código de erro: ${responseText.trace_id}\nDescrição do erro: ${responseText.error.description}`);
                            reject(responseText.error);
                        } catch (parseError) {
                            console.error('Erro HTTP:', xhr.status);
                            console.error(`Descrição do erro: ${xhr.responseText}`);
                            reject({
                                xhr: xhr,
                                description: xhr.responseText
                            });
                        }
                    } else {
                        reject({
                            xhr: xhr,
                            description: 'Erro interno no servidor API.'
                        });
                    }
                }
            });
        });

    }

    /**
     * Sends a 'POST' or 'PUT' request to the API with the provided data and returns a Promise.
     *
     * @returns {Promise} - A Promise that resolves with the response data or rejects with an error message.
     */
    saveData() {
        const self = this;

        return new Promise((resolve, reject) => {

            let param = '';
            if (this.#param != null) {
                param = this.#param;
            }

            if (globalDebug === true) {
                console.log('URL = ', this.#urlApi + param);
                console.log('Param = ', param);
                console.log('Method = ', this.#action);
                console.log('Data = ', JSON.stringify(this.#data));
            }

            $.ajax({
                url: this.#urlApi + param,
                method: this.#action,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + self.getToken());
                },
                data: JSON.stringify(this.#data),
                contentType: "application/json",
                success: function (response, status, xhr) {

                    if (globalDebug === true) {
                        console.log('Response = ', response);
                    }

                    if ([200, 201].includes(xhr.status)) {
                        resolve(response);
                    } else if (xhr.status === 401) {
                        reject('Este usuário não possui permissão para realizar esta ação.');
                    } else {
                        reject('Solicitação retornou código de status não esperado: ' + xhr.status);
                    }

                },
                error: function (xhr) {
                    if (globalDebug === true) {
                        console.error(xhr);
                    }

                    if (xhr.status === 401) {
                        reject({
                            status: xhr.status,
                            description: 'Este usuário não possui permissão para realizar esta ação.'
                        });
                    } else if (xhr.responseText) {
                        try {
                            const responseText = JSON.parse(xhr.responseText);
                            console.error('Erro HTTP:', xhr.status);
                            console.error(`Código de erro: ${responseText.trace_id}\nDescrição do erro: ${responseText.error.description}`);
                            reject(responseText.error);
                        } catch (parseError) {
                            console.error('Erro HTTP:', xhr.status);
                            console.error(`Descrição do erro: ${xhr.responseText}`);
                            reject({
                                xhr: xhr,
                                description: xhr.responseText
                            });
                        }
                    } else {
                        reject({
                            xhr: xhr,
                            description: 'Erro interno no servidor API.'
                        });
                    }
                }
            });
        });
    }

    /**
     * Sends a 'DELETE' request to the API and returns a Promise that resolves with the response data.
     *
     * @returns {Promise} - A Promise that resolves with the response data or rejects with an error message.
     */
    deleteData() {
        const self = this;

        return new Promise((resolve, reject) => {

            if (globalDebug === true) {
                console.log('URL = ', this.#urlApi + this.#param);
                console.log('Param = ', this.#param);
                console.log('Method = ', this.#action);
                console.log('Data = ', JSON.stringify(this.#data));
            }

            $.ajax({
                url: this.#urlApi + this.#param,
                method: this.#action,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + self.getToken());
                },
                contentType: "application/json",
                success: function (response, status, xhr) {

                    if (xhr.status === 200 || xhr.status === 204) {
                        resolve(response);
                    } else {
                        reject('Solicitação retornou código de status não esperado: ' + xhr.status);
                    }

                },
                error: function (xhr) {

                    if (globalDebug === true) {
                        console.log(xhr);
                    }

                    const responseText = JSON.parse(xhr.responseText);
                    if (xhr.status !== 200) {
                        console.error('Erro HTTP:', xhr.status);
                        console.error(`Código de erro: ${responseText.trace_id}\nDescrição do erro: ${responseText.error.description}`);
                    } else {
                        console.error('Erro inesperado', xhr);
                    }

                    reject(responseText.error);
                }
            });
        });
    }

    getToken() {

        return commonFunctions.getItemLocalStorage('token_stylus');

    }
}

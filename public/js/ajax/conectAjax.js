import { enumAction } from "../common/enumAction.js";

/**
 * The `conectAjax` class provides methods for making AJAX requests to an external API and handling the responses.
 */
export class conectAjax {

    /**
     * O URL base da API.
     * @type {string}
    */
    #urlApi;
    /**
     * A ação HTTP para registrar clientes (por exemplo, 'POST', 'PUT').
     * @type {string}
    */
    #action;
    /**
     * Os dados a serem enviados com a solicitação (para ações 'POST' ou 'PUT').
     * @type {string}
    */
    #data;
    /**
     * O parâmetro adicional a ser incluído na URL.
     * @type {string}
    */
    #param;

    /**
     * Cria uma nova instância `conectAjax`.
     *
     * @param {string} urlApi – O URL base da API para fazer solicitações.
     */
    constructor(urlApi) {
        this.#urlApi = urlApi;
        this.#action = null;
        this.#data = null;
        this.#param = null;
    }

    /**
     * Define a ação HTTP para registrar clientes.
     *
     * @param {string} action - A ação HTTP a ser definida (por exemplo, 'POST', 'PUT').
     * @returns {boolean} - Retorna `true` se a ação for válida, caso contrário retorna `false`.
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
     * Define os dados a serem enviados com a solicitação das ações 'POST' ou 'PUT'.
     *
     * @param {Object} data – Os dados a serem enviados na solicitação.
     */
    setData(data) {
        this.#data = data;
    }

    /**
     * Define um parâmetro adicional para incluir no URL da solicitação.
     *
     * @param {string} param – O parâmetro adicional.
     */
    setParam(param) {
        this.#param = param;
    }

    /**
     * Envia uma solicitação 'GET' para a API e retorna uma Promise que resolve com os dados da resposta.
     *
     * @returns {Promise} - Uma promessa que é resolvida com os dados de resposta ou rejeitada com uma mensagem de erro.
     */
    getRequest() {
        const self = this;

        let param = self.#param != null ? self.#param : '?size=10000000';
        let method = self.#action === null ? "GET" : self.#action;
        let data = self.#data !== null ? JSON.stringify(self.#data) : null;

        if (globalDebug === true) {
            console.log('URL = ', self.#urlApi + param);
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
                url: self.#urlApi + param,
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
     * Envia uma solicitação 'POST' ou 'PUT' para a API com os dados fornecidos e retorna uma Promessa.
     *
     * @returns {Promise} - Uma promessa que é resolvida com os dados de resposta ou rejeitada com uma mensagem de erro.
     */
    envRequest() {
        const self = selfthis;

        self.addCsrfToken();

        return new Promise((resolve, reject) => {

            let param = '';
            if (self.#param != null) {
                param = self.#param;
            }

            if (globalDebug === true) {
                console.log('URL = ', self.#urlApi + param);
                console.log('Param = ', param);
                console.log('Method = ', self.#action);
                console.log('Data = ', JSON.stringify(self.#data));
            }

            $.ajax({
                url: self.#urlApi + param,
                method: self.#action,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + self.getToken());
                },
                data: JSON.stringify(self.#data),
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
     * Envia uma solicitação 'DELETE' para a API e retorna uma Promise que resolve com os dados de resposta.
     *
     * @returns {Promise} - Uma promessa que é resolvida com os dados de resposta ou rejeitada com uma mensagem de erro.
     */
    deleteRequest() {
        const self = this;

        return new Promise((resolve, reject) => {

            if (globalDebug === true) {
                console.log('URL = ', self.#urlApi + self.#param);
                console.log('Param = ', self.#param);
                console.log('Method = ', self.#action);
                console.log('Data = ', JSON.stringify(self.#data));
            }

            $.ajax({
                url: self.#urlApi + self.#param,
                method: self.#action,
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

    addCsrfToken() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        } else {
            console.error('Token CSRF não encontrado na página.');
        }
    }

}

import { enumAction } from "../common/enumAction.js";

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
    #actionRegCli;
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
        this.#actionRegCli = null;
        this.#data = null;
        this.#param = null;

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token]').attr('content')
        //     }
        // })
    }

    /**
     * Sets the HTTP action for registering clients.
     *
     * @param {string} action - The HTTP action to set (e.g., 'POST', 'PUT').
     * @returns {boolean} - Returns `true` if the action is valid, otherwise returns `false`.
     */
    setAction(action) {
        if (enumAction.isValid(action)) {
            this.#actionRegCli = action;
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
        
        let param = '';
        if (this.#param!=null){
            param = this.#param;
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                url: this.#urlApi + param,
                method: "GET",
                dataType: "json",
                success: function (response) {

                    resolve(response);

                },
                error: function (xhr, status, error) {

                    console.error('Erro na solicitação AJAX:', status, error);
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.error('Erro da API:', xhr.responseJSON.error.description);
                    }
                    reject('Erro na solicitação AJAX');
                    
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

        this.addCsrfToken(); 

        return new Promise((resolve, reject) => {
            
            let param = '';
            if (this.#param!=null){
                param = this.#param;
            }
                        
            $.ajax({
                url: this.#urlApi + param,
                method: this.#actionRegCli,
                data: JSON.stringify(this.#data),
                contentType: "application/json",
                success: function (response, status, xhr) {

                    if (xhr.status === 200) {
                        resolve(response);
                    } else {
                        reject('Solicitação retornou código de status não esperado: ' + xhr.status);
                    }

                },
                error: function (xhr) {

                    const responseText = JSON.parse(xhr.responseText);
                    if (xhr.status !== 200) {
                        console.error(xhr);
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

    /**
     * Sends a 'DELETE' request to the API and returns a Promise that resolves with the response data.
     *
     * @returns {Promise} - A Promise that resolves with the response data or rejects with an error message.
     */
    deleteData() {

        return new Promise((resolve, reject) => {
            
            $.ajax({
                url: this.#urlApi + this.#param,
                method: this.#actionRegCli,
                contentType: "application/json",
                success: function (response, status, xhr) {

                    if (xhr.status === 200 || xhr.status === 204) {
                        resolve(response);
                    } else {
                        reject('Solicitação retornou código de status não esperado: ' + xhr.status);
                    }

                },
                error: function (xhr) {

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

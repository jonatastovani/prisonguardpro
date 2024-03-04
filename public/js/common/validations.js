import { commonFunctions } from "./commonFunctions.js";

/**
 * Classe que contém métodos estáticos para aplicar diferentes tipos de validações.
 */
export class validations {
    /**
     * Aplica uma validação específica com base no tipo fornecido.
     * @param {string} type - O tipo de validação a ser aplicada.
     * @param {Object} arrInfo - Um objeto contendo informações relevantes para a validação.
     * @param {string} arrInfo.numero - O número a ser validado (por exemplo, CPF).
     * @returns {boolean} - Retorna verdadeiro se a validação for bem-sucedida, caso contrário, falso.
     */
    static apply(type, arrInfo) {
        try {
            switch (type) {
                case 'cpf':
                    return commonFunctions.validateCPF(arrInfo.numero);
                    break;
                default:
                    commonFunctions.generateNotification('O Type da verificação não foi enviado.', 'error');
                    return false;
            }
        } catch (error) {
            console.error(error);
            commonFunctions.generateNotification(error.message, 'error');
            return false;
        }
    }

    /**
     * Obtém uma matriz de tipos de validação suportados.
     * @returns {Array<string>} - Uma matriz de tipos de validação suportados.
     */
    static get getArrayValidationsTypes() {
        return ['cpf'];
    }
}

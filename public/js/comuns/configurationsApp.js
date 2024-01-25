export class configurationApp {

    static subtractDateDefault(format) {

        const momentObject = moment().subtract(1, 'weeks');

        if (format) {
            return momentObject.format(format);
        } else {
            return momentObject;
        }

    }

    static fillOptionsOrderStatus(options = {}) {
        const {
            insertFirstOption = true,
            firstOptionName = 'Selecione',
            firstOptionValue = '',
            selectedIdOption = '',
        } = options;


        const status =
            [
                'iniciado',
                'orçado',
                'projetado',
                'montado',
                'entregue',
                'instalado',
                'finalizado',
                'cancelado'
            ]

        let strOptions = '';

        if (insertFirstOption) {
            strOptions += `<option value="${firstOptionValue}">${firstOptionName}</option>`;
        }

        status.forEach(element => {

            const id = element;
            const text = element;
            const strSelected = (id == selectedIdOption ? ' selected' : '');
            strOptions += `\n<option value="${id}"${strSelected}>${text}</option>`;

        });

        return strOptions;
    }

}

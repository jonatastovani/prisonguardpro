export class configuracoesApp {

    static subtractDateDefault(format) {

        const momentObject = moment().subtract(1, 'weeks');

        if (format) {
            return momentObject.format(format);
        } else {
            return momentObject;
        }

    }

    static mascaraMatriculaSemDigito() {
        return '#.##0';
    }

}

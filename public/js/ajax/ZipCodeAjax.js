import { commonFunctions } from "../commons/commonFunctions.js";

/**
 * The `zipCode` class is designed to fetch and populate address information based on a given ZIP code using an external API.
 * It allows you to set various elements to update with the retrieved address data.
 */
export class zipCode {

    #urlApi;
    #zipCode;
    #idElemStreet;
    #idElemNeighbourhood;
    #idElemCity;
    #idElemState;
    #idElemFocus;

    /**
     * Creates a new `zipCode` instance.
     */
    constructor() {
        this.#urlApi = urlApiZipCode; // The URL of the ZIP code API.
        this.#zipCode = null; // The ZIP code to use for address lookup.
        this.#idElemStreet = null; // The ID or selector of the element to display the street information.
        this.#idElemNeighbourhood = null; // The ID or selector of the element to display the neighborhood information.
        this.#idElemCity = null; // The ID or selector of the element to display the city information.
        this.#idElemState = null; // The ID or selector of the element to display the state information.
        this.#idElemFocus = null; // The ID or selector of the element to focus after executing.
    }

    /**
     * Sets the ZIP code to use for address lookup.
     *
     * @param {string} zipCode - The ZIP code to lookup.
     */
    setZipcode(zipCode) {
        this.#zipCode = commonFunctions.returnsOnlyNumber(zipCode);
    }

    /**
     * Sets the element to display the street information.
     *
     * @param {string} idElemStreet - The ID or selector of the element.
     */
    setIdElemStreet(idElemStreet) {
        this.#idElemStreet = idElemStreet;
    }

    /**
     * Sets the element to display the neighborhood information.
     *
     * @param {string} idElemNeighbourhood - The ID or selector of the element.
     */
    setIdElemNeighbourhood(idElemNeighbourhood) {
        this.#idElemNeighbourhood = idElemNeighbourhood;
    }

    /**
     * Sets the element to display the city information.
     *
     * @param {string} idElemCity - The ID or selector of the element.
     */
    setIdElemcity(idElemCity) {
        this.#idElemCity = idElemCity;
    }

    /**
     * Sets the element to display the state information.
     *
     * @param {string} idElemState - The ID or selector of the element.
     */
    setIdElemState(idElemState) {
        this.#idElemState = idElemState;
    }

    /**
     * Sets the element to focus after executing.
     *
     * @param {string} idElemFocus - The ID or selector of the element to focus.
     */
    setIdElemFocus(idElemFocus) {
        this.#idElemFocus = idElemFocus;
    }

    /**
     * Executes the address lookup and populates the specified elements with retrieved data.
     */
    execute() {
        const self = this;

        $.ajax({
            url: this.#urlApi + this.#zipCode,
            method: 'GET',
            dataType: "json",
            success: function (response) {
                console.log(response);

                if (self.#idElemStreet !== null) {
                    $(self.#idElemStreet).val(response.street);
                    if (!response.street) {
                        self.setIdElemFocus(self.#idElemStreet);
                    }
                }
                if (self.#idElemNeighbourhood !== null) {
                    $(self.#idElemNeighbourhood).val(response.neighbourhood);
                }
                if (self.#idElemCity !== null) {
                    $(self.#idElemCity).val(response.city);
                }
                if (self.#idElemState !== null) {
                    $(self.#idElemState).val(response.state);
                }

                if (self.#idElemFocus !== null) {
                    $(self.#idElemFocus).focus();
                }
            },
            error: function (xhr, status) {
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    console.error('Response API:', xhr.responseJSON.error.description);
                }
            }
        });
    }

}

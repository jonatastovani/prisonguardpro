import { conectAjax } from "../../ajax/conectAjax.js";
import { commonFunctions } from "../../commons/commonFunctions.js";
import { enumAction } from "../../commons/enumAction.js";

export class popProducts {

    #urlApi;
    #urlApiItems;
    #idPop;
    #dataTemp;
    #action;
    #elemFocusClose;
    #actionPromisse;
    #endTime;

    constructor (urlApi, urlApiItems) {

        this.#urlApi = urlApi;
        this.#urlApiItems = urlApiItems;
        this.#idPop = "#pop-popProducts";
        this.#dataTemp = [];
        this.#action = enumAction.POST;
        this.#elemFocusClose = null;
        this.#actionPromisse = undefined;
        this.#endTime = false;

    }
    
    setAction (action) {
        this.#action = action;
    }

    setElemFocusClose (elem) {
        this.#elemFocusClose = elem;
    }

    setUrlApi (urlApi) {
        this.#urlApi = urlApi;
    }

    setData (data) {
        this.#dataTemp = data;
    }

    openPop (){

        const self = this;

        self.addButtonsEvents();
        self.fillDataAll();
        self.fillSelectItems();
        self.clearPop();

        $(self.#idPop).addClass("active");
        $(self.#idPop).find(".popup").addClass("active");

        return new Promise(function (resolve, reject) {

            const checkConfirmation = setInterval(function () {
                
                if (self.#actionPromisse !== undefined || self.#endTime) {
                    
                    clearInterval(checkConfirmation);
                    if (self.#actionPromisse !== undefined) {
                        if (self.#actionPromisse === true) {
                            resolve(self.#dataTemp);
                        } else {
                            reject();
                        }
                        self.closePop();
                    } else {
                        reject();
                    }

                    self.#actionPromisse = undefined;
                    self.#endTime = false;
            
                }

            }, 100);

        });

    }

    closePop () {

        $(this.#idPop).removeClass("active");
        $(this.#idPop).find(".popup").removeClass("active");
        $(this.#idPop).find("*").off();
        $(this.#idPop).off('keydown');
        // this.#idProduct = '';
        // this.#dataTemp = [];
        this.#actionPromisse = false;
        this.#endTime = true;

        this.clearPop();

        if (this.#elemFocusClose!==null && $(this.#elemFocusClose).length) {
            $(this.#elemFocusClose).focus();
            this.#elemFocusClose = null;
        }

    }

    clearPop () {

        $(this.#idPop).find('form')[0].reset();
        $(this.#idPop).find('form').find('select').val('');
        $(this.#idPop).find('.btnNewPop').focus();
        $(`${this.#idPop} .table tbody`).html('');
        
    }

    addButtonsEvents () {
        const self = this;

        $(self.#idPop).find(".close-btn").on("click", () => {
            self.closePop();
        });

        $(self.#idPop).find('.btnCancelPop').on('click', () => {
            self.closePop();
        });

        $(self.#idPop).find('.btnSavePop').on('click', (event) => {
            event.preventDefault();
            self.#actionPromisse = true;
        });

        $(self.#idPop).on('keydown', function (e) {
            if (e.key === 'Escape') {
                self.closePop();
                e.stopPropagation();
            }
        });
        
        $(self.#idPop).find(".btnInsertPop").on("click", (event) => {

            event.preventDefault();

            const select = $(self.#idPop).find('select[name="item_id"]');
            const id = select.val();
            const dataItems = self.#dataTemp.item_refs;

            if (!['',null,undefined].includes(id)) {

                const index = dataItems.findIndex((item)=>item.item_id==id);
                
                self.getItem(id).then(function (result) {
                        
                        dataItems.push(result);
                        self.fillDataTr (result);

                    }).catch(function(error) {

                        console.error(error);
                        console.error(`ID Item: ${id}`);
                        $.notify(`Não foi possível recuperar os dados do item.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}\nID Item: ${id}`,'error');
    
                    });
            }

            select.focus();

        });

        self.adjustTableHeight();
        
    }

    addQueryButtonEvents (tr){

        const self = this;
        const idTr = tr.attr('id');

        tr.find('.delete').on("click", function (event) {
            event.preventDefault();

            self.delButtonAction (idTr);
            
        });

        console.log(tr.find('.form-control'))
        tr.find('.form-control').on('input', function (){

            console.log('Input', $(this).attr('name'));

            const index = self.#dataTemp.item_refs.findIndex((item)=>item.idTr==idTr);
            console.log (`Index item`, index)
            const dataItem = self.#dataTemp.item_refs[index];

            let val = $(this).val().trim();
            val = (!['', undefined, null].includes(val) ? commonFunctions.removeCommasFromCurrencyOrFraction($(this).val()) : 0);
            dataItem[$(this).attr('name')] = val;

            self.calculator(idTr);

        });


    }

    adjustTableHeight() {

        const self = this;
        const screenHeight = $(window).height();
        const maxHeight = screenHeight - 250;
        $(self.#idPop).find('.table-responsive').css('max-height', maxHeight + 'px');

    }

    fillDataAll () {

        const self = this;
        const table = $(`${this.#idPop} .table tbody`);
        const data = self.#dataTemp;

        $(self.#idPop).find('.titlePop').html(data.name);   
        table.html('');

        data.item_refs.forEach(item => {

            if (item.deleted !== true) {

                self.getItem(item.item_id).then(function (result) {
                                        
                        if (item.quantity) {
                            result.quantity = item.quantity;
                        }

                        if (item.percentage_discount) {
                            result.percentage_discount = item.percentage_discount;
                        }

                        if (item.fixed_discount) {
                            result.fixed_discount = item.fixed_discount;
                        }

                        if (item.initial_price) {
                            result.initial_price = item.initial_price;
                        }

                        if (item.final_price) {
                            result.final_price = item.final_price;
                        }
                        
                        if (item.existDB === undefined) {
                            result.existDB = true;
                        }
                        
                        self.fillDataTr (result);

                    }).catch(function(error) {

                        console.error(error);
                        console.error(`ID Item: ${item.item_id}`);
                        $.notify(`Não foi possível recuperar os dados do item.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}\nID Item: ${item.item_id}`,'error');

                    });
            }
        });

    }

    // getDataAll () {

    //     const obj = new conectAjax (this.#urlApi);
    //     const table = $(`${this.#idPop} .table tbody`);
    //     const self = this;

    //     obj.getData()
    //         .then(function (response) {

    //             console.log('retorno all', response);

    //             $(self.#idPop).find('.titlePop').html(response.name);   
    //             self.#dataTemp = [];

    //             table.html('');

    //             response.item_refs.forEach(item => {

    //                 self.getItem(item.item_id).then(function (result) {
                        
    //                         const quantity = commonFunctions.formatWithCurrencyCommasOrFraction(item.quantity);
    //                         const percentage_discount = item.percentage_discount!=0 ? commonFunctions.formatWithCurrencyCommasOrFraction(item.percentage_discount): '';
    //                         const fixed_discount = item.fixed_discount!=0 ? commonFunctions.formatWithCurrencyCommasOrFraction(item.fixed_discount): '';
                            
    //                         const tr = $(self.#idPop).find(`#${result}`);
    //                         tr.find('input[name="quantity"]').val(quantity);
    //                         tr.find('input[name="percentage_discount"]').val(percentage_discount);
    //                         tr.find('input[name="fixed_discount"]').val(fixed_discount);

    //                         const index = self.#dataTemp.findIndex((product)=>product.idTr===result);
    //                         self.#dataTemp[index].initialPrice = item.initial_price;
    //                         self.#dataTemp[index].initialPrice = item.final_price;
    //                         self.#dataTemp[index].blnNew = false;
    //                         self.#dataTemp[index].existDB = true;

    //                         self.#dataTemp[index].data.fixed_discount = item.fixed_discount;
    //                         self.#dataTemp[index].data.percentage_discount = item.percentage_discount;
    //                         self.#dataTemp[index].data.quantity = item.quantity;
                            
    //                     }).catch(function(error) {

    //                         console.error(error);
    //                         console.error(`ID Item: ${item.item_id}`);
    //                         $.notify(`Não foi possível recuperar os dados do item.\nSe o problema persistir consulte o desenvolvedor.\nErro: ${commonFunctions.firstUppercaseLetter(error.description)}\nID Item: ${item.item_id}`,'error');
        
    //                     });
        
    //             });

    //             $(self.#idPop).find('.totalRegisters').html(response.item_refs.length)
    //             self.addQueryButtonEvents();

    //         })
    //         .catch(function (error) {

    //             $(self.#idPop).find('.totalRegisters').html('0');
    //             table.html('<td colspan=6>'+error+'</td>');
                
    //         });

    // }

    getItem (idItem) {
        
        const self = this;
        const table = $(`${self.#idPop} .table tbody`);

        return new Promise(function (resolve, reject) {

            const obj = new conectAjax (self.#urlApiItems);
            obj.setParam(idItem);
    
            obj.getData()
                .then(function (response) {

                    const idTr = `${response.id}${Date.now()}`;

                    let strHTML = `<tr id="${idTr}" data-item_item="${response.id}" data-item_name="${response.name}">`;
                    strHTML += `<td>${response.name}</td>`;
                    strHTML += `<td class="text-center" style="width: 15%"><span class="initial_price">R$ 0,00</span></td>`;
                    strHTML += `<td class="text-center" style="width: 10%"><input type="text" class="form-control" name="quantity" id="quantity${idTr}"></td>`;
                    strHTML += `<td class="text-center" style="width: 10%"><input type="text" class="form-control" name="fixed_discount" id="fixed_discount${idTr}"></td>`;
                    strHTML += `<td class="text-center" style="width: 10%"><input type="text" class="form-control" name="percentage_discount" id="percentage_discount${idTr}"></td>`;
                    strHTML += `<td class="text-center"><b><span class="totalPartial">0</span></b></td>`;
                    strHTML += `<td class="text-center"><b><span class="totalDiscount">0</span></b></td>`;
                    strHTML += `<td class="text-center"><b><span class="totalItem">0</span></b></td>`;
                    strHTML += `<td class="text-center" style="width: 10%"><button class="btn btn-danger delete" title="Excluir item ${response.name}"><i class="bi bi-trash"></i></button></td>`;
                    strHTML += '</tr>';

                    table.append(strHTML);

                    const dataItem = {
                        idTr: idTr,
                        item_id: response.id,
                        itemName: response.name,
                        existDB: false,
                        deleted: false,
                        initial_price: response.price,
                        final_price: 0,
                        percentage_discount: 0,
                        fixed_discount: 0,
                        quantity: 1,
                    };

                    const tr = $('#'+idTr);
                    commonFunctions.applyCustomNumberMask(tr.find('input[name="quantity"]'),{format:'#.##0,00', reverse: true});
                    commonFunctions.applyCustomNumberMask(tr.find('input[name="fixed_discount"]'),{format:'#.##0,00', reverse: true});
                    commonFunctions.applyCustomNumberMask(tr.find('input[name="percentage_discount"]'),{format:'99,99'});
                    
                    self.addQueryButtonEvents(tr);

                    resolve(dataItem);

                })
                .catch(function (error) {
    
                    reject(error);
    
                });
    
        });

    }

    fillDataTr (result) {

        const self = this;

        const tr = $(self.#idPop).find(`#${result.idTr}`);
        tr.find('.initial_price').html(commonFunctions.formatNumberToCurrency(result.initial_price));
        tr.find('input[name="quantity"]').val(result.quantity);
        tr.find('input[name="percentage_discount"]').val(result.percentage_discount);
        tr.find('input[name="fixed_discount"]').val(result.fixed_discount);
        
        let index = self.#dataTemp.item_refs.findIndex(
            (i)=>i.item_id==result.item_id && (i.deleted === undefined || i.deleted !== true)
        );

        self.#dataTemp.item_refs[index] = result;
        self.countItems();
        self.calculator(result.idTr);

    }

    countItems() {

        const count = this.#dataTemp.item_refs.filter(item => item.deleted === false).length;
        $(this.#idPop).find('.totalRegisters').html(count)

    }

    calculator (idTr) {

        const self = this;
        const index = self.#dataTemp.item_refs.findIndex((item)=>item.idTr==idTr);
        const dataItem = self.#dataTemp.item_refs[index];
        
        const totalPartial = (dataItem.quantity * dataItem.initial_price);
        const priceDifference = (totalPartial - dataItem.fixed_discount);
        const discontPercInPrice = (priceDifference * (dataItem.percentage_discount / 100));
        const totalDiscount = (dataItem.fixed_discount + discontPercInPrice);
        const totalItem = (priceDifference - discontPercInPrice);
        
        dataItem.final_price = Number(totalItem.toFixed(2));

        $(self.#idPop).find(`#${idTr}`).find('.totalPartial').html(commonFunctions.formatNumberToCurrency(totalPartial));
        $(self.#idPop).find(`#${idTr}`).find('.totalDiscount').html(commonFunctions.formatNumberToCurrency(totalDiscount));
        $(self.#idPop).find(`#${idTr}`).find('.totalItem').html(commonFunctions.formatNumberToCurrency(totalItem));

    }

    fillSelectItems(returnPromisse = false) {

        if (returnPromisse) {

            return new Promise((resolve, reject) => {
                commonFunctions.fillSelect($(this.#idPop).find('select[name="item_id"]'), this.#urlApiItems)
                    .then((result) => {
                        resolve(result);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });

        } else {

            commonFunctions.fillSelect($(this.#idPop).find('select[name="item_id"]'), this.#urlApiItems)

        }

    }
    
    delButtonAction (idTr) {
        
        const self = this;
        const index = self.#dataTemp.item_refs.findIndex((item)=>item.idTr===idTr);
        self.#dataTemp.item_refs[index].deleted = true;
        $(self.#idPop).find(`#${idTr}`).remove();
        self.countItems();

    }

}


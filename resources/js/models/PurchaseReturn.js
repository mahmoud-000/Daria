import request from "../utils/request";
import Resource from "./resource";

class PurchaseReturn extends Resource {
    constructor() {
        super("purchaseReturns");
    }

    formOptions(query) {
        return request({
            url: "/purchase_returns/form_options",
            method: "get",
            params: query
        });
    }
}

export { PurchaseReturn as default };
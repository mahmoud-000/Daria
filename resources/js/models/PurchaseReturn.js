import request from "../utils/request";
import Resource from "./resource";

class PurchaseReturn extends Resource {
    constructor() {
        super("purchaseReturns");
    }

    formOptions(query) {
        return request({
            url: "/purchaseReturns/form_options",
            method: "get",
            params: query
        });
    }
}

export { PurchaseReturn as default };
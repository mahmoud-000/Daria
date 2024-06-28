import request from "../utils/request";
import Resource from "./resource";

class SaleReturn extends Resource {
    constructor() {
        super("saleReturns");
    }

    formOptions(query) {
        return request({
            url: "/saleReturns/form_options",
            method: "get",
            params: query
        });
    }
}

export { SaleReturn as default };
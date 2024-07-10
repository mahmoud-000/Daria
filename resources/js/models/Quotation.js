import request from "../utils/request";
import Resource from "./resource";

class Quotation extends Resource {
    constructor() {
        super("quotations");
    }

    formOptions(query) {
        return request({
            url: "/quotations/form_options",
            method: "get",
            params: query
        });
    }
}

export { Quotation as default };
import request from "../utils/request";
import Resource from "./resource";

class Sale extends Resource {
    constructor() {
        super("sales");
    }

    formOptions(query) {
        return request({
            url: "/sales/form_options",
            method: "get",
            params: query
        });
    }
}

export { Sale as default };
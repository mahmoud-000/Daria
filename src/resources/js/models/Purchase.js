import request from "../utils/request";
import Resource from "./resource";

class Purchase extends Resource {
    constructor() {
        super("purchases");
    }

    formOptions(query) {
        return request({
            url: "/purchases/form_options",
            method: "get",
            params: query
        });
    }
}

export { Purchase as default };
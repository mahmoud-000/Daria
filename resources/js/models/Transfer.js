import request from "../utils/request";
import Resource from "./resource";

class Transfer extends Resource {
    constructor() {
        super("transfers");
    }

    formOptions(query) {
        return request({
            url: "/transfers/form_options",
            method: "get",
            params: query
        });
    }
}

export { Transfer as default };
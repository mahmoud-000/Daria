import request from "../utils/request";
import Resource from "./resource";

class Adjustment extends Resource {
    constructor() {
        super("adjustments");
    }

    formOptions(query) {
        return request({
            url: "/adjustments/form_options",
            method: "get",
            params: query
        });
    }
}

export { Adjustment as default };
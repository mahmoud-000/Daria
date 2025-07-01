import request from "../utils/request";
import Resource from "./resource";

class Item extends Resource {
    constructor() {
        super("items");
    }

    formOptions(query) {
        return request({
            url: "/items/form_options",
            method: "get",
            params: query
        });
    }
}

export { Item as default };
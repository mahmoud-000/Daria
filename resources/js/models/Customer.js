import request from "../utils/request";
import Resource from "./resource";

class Customer extends Resource {
    constructor() {
        super("customers");
    }

    register(resource) {
        return request({
            url: "/customers/register",
            method: "post",
            data: resource
        });
    }
}

export { Customer as default };
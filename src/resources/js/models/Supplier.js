import request from "../utils/request";
import Resource from "./resource";

class Supplier extends Resource {
    constructor() {
        super("suppliers");
    }

    register(resource) {
        return request({
            url: "/suppliers/register",
            method: "post",
            data: resource
        });
    }
}

export { Supplier as default };
import request from "../utils/request";
import Resource from "./resource";

class Delegate extends Resource {
    constructor() {
        super("delegates");
    }

    register(resource) {
        return request({
            url: "/delegates/register",
            method: "post",
            data: resource
        });
    }
}

export { Delegate as default };
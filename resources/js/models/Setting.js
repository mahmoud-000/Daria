import request from "../utils/request";
import Resource from "./resource";

class Setting extends Resource {
    constructor() {
        super("settings");
    }
    list(query) {
        return request({
            url: "/" + this.uri + "/system",
            method: "get",
            params: query
        });
    }
    createOrUpdate(resource) {
        return request({
            url: '/' + this.uri + '/system',
            method: 'post',
            data: resource
        });
    }
}

export { Setting as default };

import request from "../utils/request";
import Resource from "./resource";

class User extends Resource {
    constructor() {
        super("users");
    }

    me() {
        return request({
            url: this.uri + '/me',
            method: "get"
        });
    }

    permissions(id) {
        return request({
            url: "/" + this.uri + "/permissions",
            method: "get"
        });
    }
}

export { User as default };
import request from "../utils/request";
import Resource from "./resource";

class Role extends Resource {
    constructor() {
        super("roles");
    }
}

export { Role as default };
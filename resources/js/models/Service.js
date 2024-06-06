import request from "../utils/request";
import Resource from "./resource";

class Service extends Resource {
    constructor() {
        super("services");
    }
}

export { Service as default };
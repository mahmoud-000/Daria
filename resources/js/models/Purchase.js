import request from "../utils/request";
import Resource from "./resource";

class Purchase extends Resource {
    constructor() {
        super("purchases");
    }
}

export { Purchase as default };
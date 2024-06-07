import request from "../utils/request";
import Resource from "./resource";

class Branch extends Resource {
    constructor() {
        super("branches");
    }
}

export { Branch as default };
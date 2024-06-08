import request from "../utils/request";
import Resource from "./resource";

class Department extends Resource {
    constructor() {
        super("departments");
    }
}

export { Department as default };
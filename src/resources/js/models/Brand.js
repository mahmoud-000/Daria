import request from "../utils/request";
import Resource from "./resource";

class Brand extends Resource {
    constructor() {
        super("brands");
    }
}

export { Brand as default };
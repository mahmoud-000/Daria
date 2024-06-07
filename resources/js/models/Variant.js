import request from "../utils/request";
import Resource from "./resource";

class Variant extends Resource {
    constructor() {
        super("variants");
    }
}

export { Variant as default };
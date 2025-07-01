import request from "../utils/request";
import Resource from "./resource";

class Attribute extends Resource {
    constructor() {
        super("attributes");
    }
}

export { Attribute as default };
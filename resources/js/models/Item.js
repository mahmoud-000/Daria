import request from "../utils/request";
import Resource from "./resource";

class Item extends Resource {
    constructor() {
        super("items");
    }
}

export { Item as default };
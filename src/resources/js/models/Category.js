import request from "../utils/request";
import Resource from "./resource";

class Category extends Resource {
    constructor() {
        super("categories");
    }
}

export { Category as default };
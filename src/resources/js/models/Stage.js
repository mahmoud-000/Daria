import request from "../utils/request";
import Resource from "./resource";

class Stage extends Resource {
    constructor() {
        super("stages");
    }
}

export { Stage as default };
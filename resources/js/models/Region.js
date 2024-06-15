import request from "../utils/request";
import Resource from "./resource";

class Region extends Resource {
    constructor() {
        super("regions");
    }
}

export { Region as default };
import request from "../utils/request";
import Resource from "./resource";

class Unit extends Resource {
    constructor() {
        super("units");
    }
}

export { Unit as default };
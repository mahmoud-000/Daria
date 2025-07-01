import request from "../utils/request";
import Resource from "./resource";

class Warehouse extends Resource {
    constructor() {
        super("warehouses");
    }
}

export { Warehouse as default };
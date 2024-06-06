import request from "../utils/request";
import Resource from "./resource";

class Company extends Resource {
    constructor() {
        super("companies");
    }
}

export { Company as default };
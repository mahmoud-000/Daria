import request from "../utils/request";
import Resource from "./resource";

class Job extends Resource {
    constructor() {
        super("jobs");
    }
}

export { Job as default };
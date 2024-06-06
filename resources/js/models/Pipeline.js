import request from "../utils/request";
import Resource from "./resource";

class Pipeline extends Resource {
    constructor() {
        super("pipelines");
    }
}

export { Pipeline as default };
import request from "../utils/request";
import Resource from "./resource";

class Upload extends Resource {
    constructor() {
        super("uploads");
    }
    
    destroy(upload) {
        return request({
            url: "/" + this.uri + "/destroy",
            method: "delete",
            data: upload
        });
    }

    reorder(images) {
        return request({
            url: "/" + this.uri + "/reorder",
            method: "put",
            data: images
        });
    }
}

export { Upload as default };
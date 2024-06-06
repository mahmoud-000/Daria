import request from '../utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
    constructor(uri) {
        this.uri = uri;
    }
    list(query) {
        return request({
            url: "/" + this.uri,
            method: "get",
            params: query
        });
    }
    get(id) {
        return request({
            url: "/" + this.uri + "/" + id,
            method: "get"
        });
    }
    store(resource) {
        return request({
            url: "/" + this.uri,
            method: "post",
            data: resource
        });
    }
    update(id, resource) {
        return request({
            url: "/" + this.uri + "/" + id,
            method: "put",
            data: resource
        });
    }
    destroy(id) {
        return request({
            url: "/" + this.uri + "/" + id,
            method: "delete"
        });
    }
    bulk_destroy(resource) {
        return request({
            url: "/" + this.uri + "/bulk_destroy",
            method: "post",
            data: resource
        });
    }
    upload(file) {
        return request({
            url: "/" + this.uri,
            method: "post",
            data: file,
            headers: { 'content-type': 'multipart/form-data' },
        });
    }
    importCSV(resource) {
        return request({
            url: "/" + this.uri + "/import_csv",
            method: "post",
            data: resource
        });
    }
    options(query) {
        return request({
            url: "/" + this.uri + "/options",
            method: "get",
            params: query
        });
    }

    exportPDF(id) {
        return request({
            url: "/" + this.uri + "/" + id + "/export_pdf",
            method: "post",
            responseType: 'blob'
        });
    }
}

export { Resource as default };

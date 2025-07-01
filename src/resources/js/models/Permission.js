import request from "../utils/request";
import Resource from "./resource";

class Permission extends Resource {
    constructor() {
        super("permissions");
    }

    // allPermissions() {
    //     return request({
    //       url: '/' + this.uri + '/permissions',
    //       method: 'get'
    //     });
    //   }
    
    //   usersByPermission(permission) {
    //     return request({
    //       url: '/' + this.uri + '/usersByPermission/' + permission,
    //       method: 'get',
    //     });
    //   }
      
    //   permissions(id) {
    //     return request({
    //       url: '/' + this.uri + '/' + id + '/permissions',
    //       method: 'get',
    //     });
    //   }
}

export { Permission as default };
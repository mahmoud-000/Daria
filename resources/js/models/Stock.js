import request from "../utils/request";
import Resource from "./resource";

class Stock extends Resource {
  constructor() {
    super("stock");
  }

  stockByWarehouse(options) {
    return request({
      url: '/stock/by_warehouse',
      method: 'get',
      params: options
    });
  }
}

export { Stock as default };
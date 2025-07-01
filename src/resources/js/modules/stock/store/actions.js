import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Stock from '../../../models/Stock'
const StockModel = new Stock('stocks')

export const fetchStocks = ({ commit }, options) => {
    return new Promise((resolve, reject) => {
        StockModel.list(options).then(res => {
            commit('SET_STOCKS', res.data)
            commit('SET_PAGINATION', { meta: res.meta, options })
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const fetchStock = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        StockModel.get(id).then(res => {
            commit('SET_STOCK', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createStock = async ({ dispatch }, stock) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        StockModel.store(stock).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updateStock = async ({ dispatch }, stock) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        StockModel.update(stock.id, stock).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyStock = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        StockModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const fetchStockByWarehouse = ({ commit }, options) => {
    return new Promise((resolve, reject) => {
        StockModel.stockByWarehouse(options).then(res => {
            commit('SET_STOCK_BY_WAREHOUSE', res.data)
            commit('SET_META', res.meta)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};
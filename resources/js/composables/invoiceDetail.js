import { computed } from 'vue';
import store from '../store'
import { floatify } from '../utils/helpers';

export const useInvoiceDetail = (costOrPrice) => {
    const units = computed(() => store.getters["unit/getOptions"]);

    const discountNetByType = (detail) => {
        // Fixed
        detail.discount = !detail.discount || detail.discount < 0 ? 0 : detail.discount * 1
        detail.quantity = !detail.quantity || detail.quantity <= 0 ? 1 : detail.quantity * 1
    
        if (detail.discount_type == 1) {
            detail.discount_net = detail.discount;
        } else {
            // Percent
            detail.discount_net = detail.amount * (detail.discount / 100);
        }
        return detail.discount_net * detail.quantity;
    };

    const netByTaxType = (detail) => {
        detail.amount = !detail.amount || detail.amount < 0 ? 0 : detail.amount * 1

        if (detail.tax_type == 1) {
            detail[`net_${costOrPrice}`] = floatify(
                detail.amount - detail.discount_net
            );
        } else {
            detail[`net_${costOrPrice}`] = floatify(
                (detail.amount - detail.discount_net) / (1 + (detail.tax / 100))
            );
        }
        return detail[`net_${costOrPrice}`];
    };

    const taxNetByTaxType = (detail) => {
        detail.tax = !detail.tax || detail.tax < 0 ? 0 : detail.tax * 1
        detail.amount = !detail.amount || detail.amount < 0 ? 0 : detail.amount * 1
        detail.quantity = !detail.quantity || detail.quantity <= 0 ? 1 : detail.quantity * 1
        
        if (detail.tax_type == 1) {
            detail.tax_net = floatify(
                ((detail.tax * (detail.amount - detail.discount_net)) / 100) * detail.quantity
            );
        } else {
            detail.tax_net = floatify(
                (detail.amount -
                    detail[`net_${costOrPrice}`] -
                    detail.discount_net) * detail.quantity
            );
        }
        return detail.tax_net;
    };

    const unitSideEffectDivide = (detail, unit) => {
        detail.stocky = detailStocky(detail, unit);
        detail.discount_net = discountNetByType(detail);
        detail[`net_${costOrPrice}`] = netByTaxType(detail);
        detail.tax_net = taxNetByTaxType(detail);
        detail.total = detailSubTotal(detail, unit);
        return detail;
    };

    const unitSideEffectMultiply = (detail, unit) => {
        detail.stocky = detailStocky(detail, unit);
        detail.discount_net = discountNetByType(detail);
        detail[`net_${costOrPrice}`] = netByTaxType(detail);
        detail.tax_net = taxNetByTaxType(detail);
        detail.total = detailSubTotal(detail, unit);
        return detail;
    };

    const detailStocky = (detail, unit) => {
        if (unit.operator === "/") {
            return detail.stock * unit.operator_value;
        }

        if (unit.operator === "*") {
            return detail.stock / unit.operator_value;
        }
    };

    const detailSubTotal = (detail, unit) => {
        detail.quantity = !detail.quantity || detail.quantity <= 0 ? 1 : detail.quantity * 1
        return floatify(
            detail[`net_${costOrPrice}`] * detail.quantity + detail.tax_net
        );
    };

    const detailCalculate = (detail) => {
        detail.quantity = !detail.quantity || detail.quantity <= 0 ? 1 : detail.quantity * 1
        units.value.forEach((unit) => {
            if (unit.id === detail.unit_id) {
                detail.unit = unit.short_name;
                unit.operator === "/"
                    ? unitSideEffectDivide(detail, unit)
                    : unitSideEffectMultiply(detail, unit);
            }
        });
    };

    return {
        detailCalculate
    }
};

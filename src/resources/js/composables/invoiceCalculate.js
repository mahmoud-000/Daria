import { floatify } from '../utils/helpers'

export const useInvoice = (formData, costOrPrice) => {

    const paidAmount = () => {
        return formData.payments?.reduce(
            (payment, arr) => payment + +arr.amount,
            0
        ) ?? 0;
    };

    const due = () => {
        return floatify(formData.grand_total - formData.paid_amount);
    };

    const grandTotal = () => {
        formData.other_expenses = !formData.other_expenses || formData.other_expenses < 0 ? 0 : formData.other_expenses * 1

        return floatify(
            totalAfterDiscount() +
            +formData.tax_net +
            +formData.other_expenses
        );
    };

    const shippingNetByType = () => {
        formData.shipping = !formData.shipping || formData.shipping < 0 ? 0 : formData.shipping * 1

        // Percent
        if (formData.commission_type === 2) {
            formData.shipping_net = floatify(grandTotal() * (formData.shipping / 100));
        } else {
            // Fixed
            formData.shipping_net = formData.shipping;
        }
        
        return +formData.shipping_net
    };

    const discountNetByType = () => {
        formData.discount = !formData.discount || formData.discount < 0 ? 0 : formData.discount * 1

        // Fixed
        if (formData.discount_type == 1) {
            formData.discount_net = formData.discount;
        } else {
            // Percent
            formData.discount_net = floatify(+sumDetailsSubtotal() * (formData.discount / 100));
        }
        return formData.discount_net
    };

    const taxNet = () => {
        formData.tax = !formData.tax || formData.tax < 0 ? 0 : formData.tax * 1
        return floatify((totalAfterDiscount() * formData.tax) / 100);
    };

    const totalAfterDiscount = () => {
        return floatify(+sumDetailsSubtotal() - discountNetByType());
    };

    const sumDetailsSubtotal = () => {
        return formData.details.reduce(
            (detail, arr) =>
                floatify(
                    detail +
                    +arr.quantity * +arr[`net_${costOrPrice}`] +
                    +arr.tax_net
                ),
            0
        );
    };

    const invoiceCalculation = () => {
        formData.tax_net = taxNet().toFixed(2);
        formData.grand_total = grandTotal() + shippingNetByType();
        formData.paid_amount = paidAmount();
        formData.due = due();
    };

    return {
        invoiceCalculation
    }
}
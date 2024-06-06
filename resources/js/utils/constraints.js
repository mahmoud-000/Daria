import { reactive } from "vue";
import { helpers } from "@vuelidate/validators";
import i18n from "../i18n";
const { t } = i18n.global

export const paymentStatus = reactive([
    { value: 1, label: t("select.payment_status.paid") },
    { value: 2, label: t("select.payment_status.unpaid") },
    { value: 3, label: t("select.payment_status.partial") },
]);

export const statusTypes = reactive([
    { value: 0, label: t("select.status.not_active") },
    { value: 1, label: t("select.status.active") },
]);

export const adjustmentTypes = reactive([
    { value: 1, label: t("select.adjustment.addition") },
    { value: 2, label: t("select.adjustment.subtraction") },
]);

export const genderTypes = reactive([
    { value: 1, label: t("select.gender.male") },
    { value: 2, label: t("select.gender.female") },
]);

export const barcodeTypes = reactive([
    { value: 1, label: "CODE128" },
    { value: 2, label: "CODE39" },
    { value: 3, label: "EAN8" },
    { value: 4, label: "EAN13" },
    { value: 5, label: "UPC" },
]);

export const taxTypes = reactive([
    { value: 1, label: t("select.tax.exclusive") },
    { value: 2, label: t("select.tax.inclusive") },
]);

export const fpTypes = reactive([
    { value: 1, label: t("select.discount.fixed") },
    { value: 2, label: t("select.discount.percent") },
]);

export const itemTypes = reactive([
    { value: 1, label: t("select.item_type.standard") },
    { value: 2, label: t("select.item_type.variable") },
    { value: 3, label: t("select.item_type.service") },
]);

export const productTypes = reactive([
    { value: 1, label: t("select.product_type.stock") },
    { value: 2, label: t("select.product_type.consumer") },
]);

export const icTypes = reactive([
    { value: 1, label: t("select.delegate_type.individual") },
    { value: 2, label: t("select.delegate_type.company") },
]);

export const variant = reactive({
    name: "",
    code: "",
    cost: 0,
    price: 0,
    color: "#FFFFFF",
    // default: false,
});

export const stage = reactive({
    name: "",
    color: "#000000",
    complete: 0,
    default: false
});

export const operators = reactive([
    { label: t('select.operators.multiply'), value: "*" },
    { label: t('select.operators.divide'), value: "/" }
]);

export const moduleNames = reactive([
    { value: 'purchase', label: t("select.module_names.purchase") },
    { value: 'purchase_return', label: t("select.module_names.purchase_return") },
    { value: 'sale', label: t("select.module_names.sale") },
    { value: 'sale_return', label: t("select.module_names.sale_return") },
]);

export const branch = reactive({
    name: "",
    email: "",
    phone: "",
    mobile: "",
    country: null,
    city: null,
    state: "",
    address: "",
    zip: "",
    is_main: 0,
    is_active: 0
});

export const contact = reactive({
    name: "",
    email: "",
    phone: "",
    mobile: "",
});

export const location = reactive({
    name: "",
    country: null,
    city: null,
    state: "",
    first_address: "",
    second_address: "",
    zip: "",
});

export const drivers = reactive([{ value: "smtp", label: t("select.driver.smtp") }]);

export const containsUppercase = (value) => !helpers.req(value) || /[A-Z]/.test(value);
export const containsLowercase = (value) => !helpers.req(value) || /[a-z]/.test(value);
export const containsNumber = (value) => !helpers.req(value) || /[0-9]/.test(value);
export const containsSpecial = (value) =>
    !helpers.req(value) || /[#?!@$%^&*-]/.test(value);
export const slugRule = (value) =>
    !helpers.req(value) || !/[#?!@$%^&~,.<>;':"\/\[\]\|{}\(\)=+*]/.test(value);

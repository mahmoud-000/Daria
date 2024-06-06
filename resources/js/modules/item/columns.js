import { reactive } from "vue";
import i18n from "../../i18n";
import { statusTypes, itemTypes } from "../../utils/constraints";
const { t } = i18n.global

export const columns = reactive([
    {
        name: "id",
        required: true,
        label: t('id'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "active_image",
        required: false,
        label: t('table.active_image'),
        align: "center",
        field: (row) => row.active_image,
        format: (val) => `${val.url}`,
        sortable: false,
    },

    {
        name: "type",
        required: false,
        label: t('type'),
        align: "center",
        field: (row) => row.type,
        format: (val) => `${itemTypes.find(st => st.value === val).label}`,
        sortable: true,
    },
    // Name if Item is Standard
    // List Names Variants if Item is Variable
    {
        name: "name",
        required: false,
        label: t('name'),
        align: "center",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "code",
        required: true,
        label: t('code'),
        align: "center",
        field: (row) => row.code,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "category",
        required: false,
        label: t('category_id'),
        align: "center",
        field: (row) => row.category,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "brand",
        required: false,
        label: t('brand_id'),
        align: "center",
        field: (row) => row.brand,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    // Cost
    {
        name: "cost",
        required: false,
        label: t('cost'),
        align: "center",
        field: (row) => row.cost,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },
    // Price
    {
        name: "price",
        required: false,
        label: t('price'),
        align: "center",
        field: (row) => row.price,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },
    // Unit
    {
        name: "unit",
        required: false,
        label: t('unit_id'),
        align: "center",
        field: (row) => row.unit,
        format: (val) => `${val ?? '- - -'}`,
        sortable: false,
    },
    // Quantity
    {
        name: "quantity",
        required: false,
        label: t('table.quantity'),
        align: "center",
        field: (row) => row,
        format: (val) => `${val.quantity ?? 0} ${val.unit}`,
        sortable: false,
    },

    {
        name: "is_active",
        required: false,
        label: t('is_active'),
        align: "center",
        field: (row) => row.is_active,
        format: (val) => `${statusTypes.find(st => st.value === val).label}`,
        sortable: true,
    },

    {
        name: "is_available_for_purchase",
        required: false,
        label: t('table.is_available_for_purchase'),
        align: "center",
        field: (row) => row.is_available_for_purchase,
        format: (val) => `${statusTypes.find(st => st.value === val).label}`,
        sortable: true,
    },

    {
        name: "is_available_for_sale",
        required: false,
        label: t('table.is_available_for_sale'),
        align: "center",
        field: (row) => row.is_available_for_sale,
        format: (val) => `${statusTypes.find(st => st.value === val).label}`,
        sortable: true,
    },

    {
        name: "created_at",
        required: false,
        label: t('created_at'),
        align: "center",
        field: (row) => row.created_at,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "updated_at",
        required: false,
        label: t('updated_at'),
        align: "center",
        field: (row) => row.updated_at,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "actions",
        required: false,
        label: t('actions'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
    },
]);

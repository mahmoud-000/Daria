import { reactive } from "vue";
import i18n from "../../i18n";
import { statusTypes, paymentStatus } from "../../utils/constraints";
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
        name: "ref",
        required: false,
        label: t('table.ref'),
        align: "center",
        field: (row) => row.ref,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "date",
        required: false,
        label: t('date'),
        align: "center",
        field: (row) => row.date,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "warehouse",
        required: false,
        label: t('warehouse_id'),
        align: "center",
        field: (row) => row.warehouse,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "supplier",
        required: false,
        label: t('supplier_id'),
        align: "center",
        field: (row) => row.supplier,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "pipeline",
        required: false,
        label: t('pipeline_id'),
        align: "center",
        field: (row) => row.pipeline,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "paid_amount",
        required: false,
        label: t('table.paid_amount'),
        align: "center",
        field: (row) => row.paid_amount,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "payment_status",
        required: false,
        label: t('table.payment_status'),
        align: "center",
        field: (row) => row.payment_status,
        format: (val) => `${paymentStatus.find(st => st.value === val).label}`,
        sortable: false,
    },

    {
        name: "grand_total",
        required: false,
        label: t('table.grand_total'),
        align: "center",
        field: (row) => row.grand_total,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "due",
        required: false,
        label: t('table.due'),
        align: "center",
        field: (row) => row.due,
        format: (val) => `${val}`,
        sortable: false,
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

import { reactive } from "vue";
import i18n from "../../i18n";
const { t } = i18n.global
import { ticketStatus } from "../../utils/constraints";

export const columns = reactive([
    {
        name: "serial_number",
        required: true,
        label: t('serial_number'),
        align: "center",
        field: (row) => row.serial_number,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "service",
        required: false,
        label: t('service'),
        align: "center",
        field: (row) => row.service,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "device_serial_number",
        required: false,
        label: t('device_serial_number'),
        align: "center",
        field: (row) => row.device_serial_number,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "deadline",
        required: false,
        label: t('deadline'),
        align: "center",
        field: (row) => row.deadline,
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

export const columnsRMA = reactive([
    {
        name: "serial_number",
        required: true,
        label: t('serial_number'),
        align: "center",
        field: (row) => row.serial_number,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "service",
        required: false,
        label: t('service'),
        align: "center",
        field: (row) => row.service,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "device_serial_number",
        required: false,
        label: t('device_serial_number'),
        align: "center",
        field: (row) => row.device_serial_number,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "deadline",
        required: false,
        label: t('deadline'),
        align: "center",
        field: (row) => row.deadline,
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
        name: "rma_at",
        required: false,
        label: t('rma_at'),
        align: "center",
        field: (row) => row.rma_at,
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

export const columnsClosed = reactive([
    {
        name: "serial_number",
        required: true,
        label: t('serial_number'),
        align: "center",
        field: (row) => row.serial_number,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "service",
        required: false,
        label: t('service'),
        align: "center",
        field: (row) => row.service,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "device_serial_number",
        required: false,
        label: t('device_serial_number'),
        align: "center",
        field: (row) => row.device_serial_number,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "deadline",
        required: false,
        label: t('deadline'),
        align: "center",
        field: (row) => row.deadline,
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
        name: "closed_at",
        required: false,
        label: t('closed_at'),
        align: "center",
        field: (row) => row.closed_at,
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

export const columnsPriceLists = reactive([
    {
        name: "service",
        required: false,
        label: t('service'),
        align: "center",
        field: (row) => row.service,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "price",
        required: false,
        label: t('price'),
        align: "center",
        field: (row) => row.price,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "deadline",
        required: false,
        label: t('deadline'),
        align: "center",
        field: (row) => row.deadline,
        format: (val) => `${val}`,
        sortable: false,
    },
]);

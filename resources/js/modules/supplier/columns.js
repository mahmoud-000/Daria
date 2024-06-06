import { reactive } from "vue";
import i18n from "../../i18n";
import { icTypes, statusTypes } from "../../utils/constraints";
const { t } = i18n.global

export const columns = reactive([
    {
        name: "id",
        required: false,
        label: t('id'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: true,
    },
    {
        name: "avatar",
        required: false,
        label: t('logo'),
        align: "center",
        field: (row) => row.avatar,
        format: (val) => `${val.url}`,
        sortable: false,
    },
    {
        name: "fullname",
        required: false,
        label: t('fullname'),
        align: "center",
        field: (row) => row.fullname,
        format: (val) => `${val}`,
        sortable: false,
    },
    {
        name: "email",
        required: false,
        label: t('email'),
        align: "center",
        field: (row) => row.email,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "type",
        required: false,
        label: t('type'),
        align: "center",
        field: (row) => row.type,
        format: (val) => `${icTypes.find(st => st.value === val).label}`,
        sortable: true,
    },

    {
        name: "company_name",
        required: false,
        label: t('company_name'),
        align: "center",
        field: (row) => row.company_name,
        format: (val) => `${val ?? t('table.no_default')}`,
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

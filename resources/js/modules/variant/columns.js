import { reactive } from "vue";
import i18n from "../../i18n";
import { statusTypes } from "../../utils/constraints";
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
        name: "name",
        required: false,
        label: t('name'),
        align: "center",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: false
    },

    {
        name: "code",
        required: false,
        label: t('code'),
        align: "center",
        field: (row) => row.code,
        format: (val) => `${val}`,
        sortable: false
    },

    {
        name: "cost",
        required: false,
        label: t('cost'),
        align: "center",
        field: (row) => row.cost,
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
        name: "item",
        required: false,
        label: t('item_id'),
        field: (row) => row.item,
        format: (val) => `${val.name}`,
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
        name: "actions",
        required: false,
        label: t('actions'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
    },
]);

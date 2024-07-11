<script setup>
import { Notify, Screen } from "quasar";
import { ref, computed, toRefs, watch, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import { useExportTableCsv } from "../../composables/csv";
import { useTable } from "../../composables/table";
import { useVisibleColumns } from "../../composables/visibleColumns";
import { BaseInput, BaseBtn, DialogConfirm, VisibleColumns } from "../import";

const props = defineProps({
    config: {
        type: Object,
        required: true,
        default: () => {},
    },
    columns: {
        type: Array,
        required: true,
        default: () => [],
    },
});
const store = useStore();
const router = useRouter();
const route = useRoute();
const { t } = useI18n();
const { config, columns } = toRefs(props);
const {
    moduleName,
    getItems,
    fetchItems,
    bulkDestroyItems,
    destroyItem,
    options,
} = config.value;
const { visibleColumns } = useVisibleColumns(columns.value);
const selected = ref([]);
const search = ref(route.query.search);
const dialogConfirm = ref(false);

const loading = computed(() => store.getters["getLoading"], { root: true });
const toggleView = ref(route.query.view);

const rows = computed(() => store.getters[`${moduleName}/${getItems}`]);
const { exportTableCsv } = useExportTableCsv(
    moduleName,
    columns,
    rows,
    visibleColumns
);

const { handleRequest, pagination } = useTable(moduleName, fetchItems);
const onRequest = async (tableOptions) => {
    router.replace({
        query: {
            page: tableOptions.pagination.page,
            rowsPerPage: tableOptions.pagination.rowsPerPage,
            sortBy: tableOptions.pagination.sortBy,
            descending: tableOptions.pagination.descending,
            search: tableOptions.filter,
            view: toggleView.value,
        },
    });

    await handleRequest(tableOptions);
    const getPagination = computed(
        () => store.getters[`${moduleName}/getPagination`]
    );

    const { page, rowsNumber, rowsPerPage, sortBy, descending } =
        getPagination.value;

    pagination.value.rowsNumber = rowsNumber;
    pagination.value.rowsPerPage = rowsPerPage;
    pagination.value.page = page;
    pagination.value.sortBy = sortBy;
    pagination.value.descending = descending;
};

watch(search, (val) => {
    router.replace({ query: { search: val } });
});

watch(
    toggleView,
    (val) => {
        router.replace({ query: { ...route.query, view: val } });
    },
    { immediate: true }
);

const getSelectedString = () => {
    return selected.value.length === 0
        ? ""
        : t("table.selected_records_label", {
              length: selected.value.length,
              rows: rows.value.length,
          });
};

const editItem = (id) => {
    router.push({ name: `${moduleName}.edit`, params: { id } });
};

const showItem = (id) => {
    router.push({ name: `${moduleName}.show`, params: { id } });
};

const deletedMessage = ref("");
const deletedItemId = ref(null);

const confirmDelete = (id) => {
    deletedMessage.value = t("messages.delete_confirm");
    deletedItemId.value = id;
    dialogConfirm.value = true;
};

const deletedItemIds = ref([]);
const confirmBulkDelete = () => {
    if (!selected.value.length) {
        Notify.create({
            progress: true,
            type: "negative",
            message: t("messages.delete_no_selected"),
        });
        return;
    }
    deletedMessage.value = t("messages.bulk_delete_confirm");
    deletedItemIds.value = selected.value.map((item) => item.id);
    dialogConfirm.value = true;
};

const deleteItem = async () => {
    dialogConfirm.value = false;

    if (deletedItemIds.value.length) {
        await store.dispatch(`${moduleName}/${bulkDestroyItems}`, {
            ids: deletedItemIds.value,
        });
    }
    if (deletedItemId.value !== null) {
        await store.dispatch(
            `${moduleName}/${destroyItem}`,
            deletedItemId.value
        );
    }
    deletedItemId.value = null;
    deletedItemIds.value = [];
    selected.value = [];
    // Refresh Table With New Get Items Request
    onRequest({
        pagination: pagination.value,
        filter: search.value,
    });
};

const clearDeletArray = () => {
    selected.value = [];
    deletedItemId.value = null;
    deletedItemIds.value = [];
};

const exportPDF = async (id) => {
    await store.dispatch(`${moduleName}/exportPdf`, id);
};

onMounted(() => {
    onRequest({ pagination: pagination.value, filter: search.value });
});
</script>
<template>
    <q-table
        :grid="toggleView === 'grid' || Screen.lt.md"
        class="q-py-md"
        :rows-per-page-options="[10, 25, 50]"
        :rows="rows"
        :columns="columns"
        row-key="id"
        v-model:pagination="pagination"
        :loading="loading"
        @request="onRequest"
        binary-state-sort
        :dense="Screen.lt.md"
        separator="none"
        :visible-columns="visibleColumns"
        :selected-rows-label="getSelectedString"
        selection="multiple"
        v-model:selected="selected"
        :no-data-label="t('table.no_data_label')"
        :no-results-label="t('table.no_results_label')"
        :filter="search"
        flat
        bordered
        :class="!$q.dark.isActive ? 'bg-white' : 'bg-dark'"
    >
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>

        <template #no-data="{ icon, message, filter }">
            <div class="full-width row flex-center text-accent q-gutter-sm">
                <q-icon size="2em" name="fa-regular fa-face-frown-open" />
                <span> {{ message }} </span>
                <q-icon size="2em" :name="filter ? 'fa-solid fa-filter' : icon" />
            </div>
        </template>

        <template #top-left="props">
            <div
                class="col-lg-12 col-md-12 col-sm-12 col-xs-12 flex items-center"
                :class="$q.screen.gt.sm ? 'justify-between' : 'justify-center'"
            >
                <div class="q-gutter-y-md col-auto q-mb-md">
                    <q-tabs
                        v-model="toggleView"
                        dense
                        narrow-indicator
                        align="justify"
                    >
                        <q-tab name="table" icon="fa-solid fa-table-list" />
                        <q-tab name="grid" icon="fa-solid fa-grip" />
                    </q-tabs>
                </div>
                <div class="col-auto q-mb-md row justify-center">
                    <VisibleColumns
                        v-model="visibleColumns"
                        :columns="columns"
                    />
                </div>
            </div>
        </template>

        <template #top-right="props">
            <div
                class="col-lg-12 col-md-12 col-sm-12 col-xs-12 flex items-center"
                :class="$q.screen.gt.sm ? 'justify-between' : 'justify-center'"
            >
                <div class="col-auto q-mb-md">
                    <BaseBtn
                        glossy
                        round
                        color="info"
                        icon="fa-solid fa-file-import"
                        class="q-ml-md"
                        :toolbar="t('table.import_csv')"
                        :to="{ name: `${moduleName}.import` }"
                        v-if="options.import"
                        v-permission="[`import-file-${moduleName}`]"
                    />

                    <BaseBtn
                        glossy
                        round
                        color="info"
                        icon="fa-solid fa-file-csv"
                        class="q-ml-md"
                        :toolbar="t('table.export_csv')"
                        @click="() => exportTableCsv()"
                        v-if="options.export"
                        v-permission="[`export-file-${moduleName}`]"
                    />

                    <BaseBtn
                        glossy
                        round
                        icon="fa-regular fa-trash-can"
                        color="negative"
                        class="q-ml-md"
                        :toolbar="t('table.bulk_delete')"
                        @click="() => confirmBulkDelete()"
                        v-permission="[`bulk-delete-${moduleName}`]"
                    />

                    <BaseBtn
                        glossy
                        round
                        dark
                        icon="fa-solid fa-plus"
                        color="primary"
                        class="q-ml-md"
                        :toolbar="t('table.new_record')"
                        :to="{ name: `${moduleName}.create` }"
                        v-permission="[`create-${moduleName}`]"
                    />
                    <BaseBtn
                        color="info"
                        class="q-ml-md"
                        :icon="
                            props.inFullscreen
                                ? 'fa-solid fa-compress'
                                : 'fa-solid fa-expand'
                        "
                        @click="props.toggleFullscreen"
                        :toolbar="t('fullscreen')"
                    />
                </div>

                <div class="col-auto q-mb-md row justify-center">
                    <BaseInput
                        v-model="search"
                        debounce="800"
                        :placeholder="t('global.search')"
                        class="q-ml-md"
                        append="search"
                        hide-hint
                        dense
                        :label="t('search')"
                        :bottom-slots="false"
                    />
                </div>
            </div>
        </template>

        <template #item="props">
            <div
                class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3"
                :style="props.selected ? 'transform: scale(0.95);' : ''"
            >
                <q-card class="bg-primary q-pa-lg" dark>
                    <q-card-section>
                        <q-checkbox
                            dense
                            v-model="props.selected"
                            :label="props.row.name"
                        ></q-checkbox>
                    </q-card-section>

                    <q-separator></q-separator>
                    <q-list dense dark>
                        <q-item
                            v-for="col in props.cols.filter(
                                (col) => col.name !== 'remarks'
                            )"
                            :key="col.name"
                        >
                            <template
                                v-if="
                                    !['actions', 'avatar', 'logo'].includes(
                                        col.name
                                    )
                                "
                            >
                                <q-item-section>
                                    <q-item-label>{{ col.label }}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-item-label caption>{{
                                        col.value
                                    }}</q-item-label>
                                </q-item-section>
                            </template>
                            <template
                                v-else-if="
                                    ['avatar', 'logo'].includes(col.name)
                                "
                            >
                                <q-item-section>
                                    <q-item-label>{{ col.label }}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-item-label caption>
                                        <q-td class="text-center" :props="props">
                                            <q-avatar size="md">
                                                <q-img :src="col.value" />
                                            </q-avatar>
                                        </q-td>
                                    </q-item-label>
                                </q-item-section>
                            </template>
                            <q-item-section v-else>
                                <div class="row justify-center">
                                    <BaseBtn
                                        glossy
                                        round
                                        class="q-ml-md"
                                        icon="fa-regular fa-file-pdf"
                                        color="secondary"
                                        :toolbar="t('table.pdf_record')"
                                        @click="exportPDF(props.row.id)"
                                        v-permission="[
                                            `export-file-${moduleName}`,
                                        ]"
                                    />
                                    <BaseBtn
                                        glossy
                                        round
                                        color="blue"
                                        class="q-ml-sm"
                                        icon="fa-regular fa-eye"
                                        :toolbar="t('table.show_record')"
                                        @click="showItem(props.row.id)"
                                        v-permission="[`show-${moduleName}`]"
                                    />

                                    <BaseBtn
                                        glossy
                                        round
                                        color="positive"
                                        icon="fa-regular fa-pen-to-square"
                                        class="q-ml-sm"
                                        :toolbar="t('table.edit_record')"
                                        @click="editItem(props.row.id)"
                                        v-permission="[`edit-${moduleName}`]"
                                    />
                                    <BaseBtn
                                        glossy
                                        round
                                        icon="fa-solid fa-xmark"
                                        color="negative"
                                        class="q-ml-sm"
                                        :toolbar="t('table.delete_record')"
                                        @click="confirmDelete(props.row.id)"
                                        v-permission="[`delete-${moduleName}`]"
                                    />
                                </div>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
        </template>

        <!-- Is Active Badge -->
        <template #body-cell-is_active="props">
            <q-td class="text-center" :props="props">
                <q-badge
                    :color="props.row.is_active ? 'positive' : 'negative'"
                    class="q-ma-none"
                    >{{ props.value }}</q-badge
                >
            </q-td>
        </template>

        <template #body-cell-actions="props">
            <q-td class="text-center" :props="props">
                <BaseBtn
                    glossy
                    round
                    class="q-ml-md"
                    icon="fa-regular fa-file-pdf"
                    color="secondary"
                    :toolbar="t('table.pdf_record')"
                    @click="exportPDF(props.value)"
                    v-if="moduleName === 'ticket'"
                    v-permission="[`export-file-${moduleName}`]"
                />
                <BaseBtn
                    glossy
                    round
                    color="blue"
                    class="q-ml-sm"
                    icon="fa-regular fa-eye"
                    :toolbar="t('table.show_record')"
                    @click="showItem(props.value)"
                    v-permission="[`show-${moduleName}`]"
                />
                <BaseBtn
                    glossy
                    round
                    color="positive"
                    icon="fa-regular fa-pen-to-square"
                    class="q-ml-sm"
                    :toolbar="t('table.edit_record')"
                    @click="editItem(props.value)"
                    v-permission="[`edit-${moduleName}`]"
                />
                <BaseBtn
                    glossy
                    round
                    icon="fa-solid fa-xmark"
                    color="negative"
                    class="q-ml-sm"
                    :toolbar="t('table.delete_record')"
                    @click="confirmDelete(props.value)"
                    v-permission="[`delete-${moduleName}`]"
                />

                <BaseBtn
                    glossy
                    round
                    icon="fa-regular fa-file-pdf"
                    color="secondary"
                    class="q-ml-sm"
                    :toolbar="t('table.pdf_record')"
                    @click="exportPDF(props.value)"
                    v-if="options.pdf"
                />
                <slot name="moreActions" />
            </q-td>
        </template>
    </q-table>

    <DialogConfirm
        @close-confirm="clearDeletArray"
        @delete-item="deleteItem"
        v-model="dialogConfirm"
        v-if="dialogConfirm"
    >
        {{ deletedMessage }}
    </DialogConfirm>
</template>

<style scoped>
.grid-style-transition {
    transition: transform 0.28s, background-color 0.28s;
}
</style>

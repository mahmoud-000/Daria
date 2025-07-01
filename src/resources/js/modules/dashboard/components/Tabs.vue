<script setup>
import { ref, reactive, watch } from "vue";
import { useStore } from "vuex";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { columns, columnsRMA, columnsClosed } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const store = useStore();
const status = ref(1);
const { t } = useI18n();

const configTickets = reactive({
    moduleName: "ticket",
    fetchItems: "fetchTickets",
    getItems: "getTickets",
    destroyItem: "destroyTicket",
    bulkDestroyItems: "bulkDestroyTickets",
    options: {
        import: false,
        export: true,
    }, // import csv - export csv
});

watch(
    status,
    (val) => {
        store.commit("ticket/SET_STATUS", val);
    },
    { immediate: true }
);
</script>

<template>
    <div class="col-12 q-mb-md">
        <q-tabs
            v-model="status"
            dense
            inline-label
            outside-arrows
            mobile-arrows
            class="col-12 shadow-2"
            :class="Dark.isActive ? 'bg-dark text-white' : 'bg-white text-dark'"
            :breakpoint="0"
        >
            <q-tab
                :name="1"
                icon="ads_click"
                :label="t('select.ticket.opened')"
            />
            <q-tab :name="2" icon="recycling" :label="t('select.ticket.rma')" />
            <q-tab :name="3" icon="check" :label="t('select.ticket.closed')" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="status" animated>
            <q-tab-panel :name="1">
                <Suspense>
                    <template #default>
                        <BaseTable :config="configTickets" :columns="columns" />
                    </template>
                    <template #fallback>
                        <div class="fixed-center">
                            <the-spinner />
                        </div>
                    </template>
                </Suspense>
            </q-tab-panel>

            <q-tab-panel :name="2">
                <Suspense>
                    <template #default>
                        <BaseTable
                            :config="configTickets"
                            :columns="columnsRMA"
                        />
                    </template>
                    <template #fallback>
                        <div class="fixed-center">
                            <the-spinner />
                        </div>
                    </template>
                </Suspense>
            </q-tab-panel>

            <q-tab-panel :name="3">
                <Suspense>
                    <template #default>
                        <BaseTable
                            :config="configTickets"
                            :columns="columnsClosed"
                        />
                    </template>
                    <template #fallback>
                        <div class="fixed-center">
                            <the-spinner />
                        </div>
                    </template>
                </Suspense>
            </q-tab-panel>
        </q-tab-panels>
    </div>
</template>

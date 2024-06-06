<script setup>
import { ref, reactive, computed } from "vue";
import {
    VueCsvToggleHeaders,
    VueCsvSubmit,
    VueCsvMap,
    VueCsvInput,
    VueCsvErrors,
    VueCsvImport,
} from "vue-csv-import";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const mappedCsv = ref(null);
const router = useRouter();
const { t } = useI18n();
const fields = reactive({
    name: { required: true, label: t("name") },
    is_draft: { required: false, label: t("is_draft") },
});
const store = useStore();
const submit = async () => {
    await store.dispatch(`unit/importCsv`, mappedCsv.value);
    mappedCsv.value = null;
    store.state.errors = [];
    router.push({ name: `unit.list` });
};

const cancelImport = () => {
    mappedCsv.value = null;
    store.state.errors = [];
    router.push({ name: `unit.list` });
}

const errors = computed(() => store.getters["getErrors"], { root: true });
</script>
<template>
    <q-card
        flat
        style="
            max-width: 70rem;
            margin-left: auto;
            margin-right: auto;
            border-radius: 20px;
        "
    >
        <q-card-section>
            <div class="row items-center justify-center">
                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <div class="text-h5">
                            {{ t("card.import_csv") }}
                        </div>
                    </div>
                </div>

                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <q-btn
                            :label="t('action.cancel')"
                            color="negative"
                            @click="() => cancelImport()"
                            class="q-mr-md"
                        />
                        <q-btn
                            :label="t('action.save')"
                            type="submit"
                            color="positive"
                            @click="submit"
                            :disabled="!mappedCsv"
                        />
                    </div>
                </div>
            </div>
        </q-card-section>
        <q-card-section class="flex justify-center items-center col-12">
            <vue-csv-import v-model="mappedCsv" :fields="fields">
                <label>
                    <vue-csv-input
                        class="bg-black q-pa-lg w-full"
                    ></vue-csv-input>
                    <span class="clickable bg-black text-white q-pa-md">{{
                        $t("action.choose_your_file")
                    }}</span>
                </label>
                <vue-csv-map></vue-csv-map>
            </vue-csv-import>
        </q-card-section>
        <div class="q-pa-sm col-12">
            <q-table
                flat
                bordered
                separator="cell"
                dense
                v-if="mappedCsv"
                :rows="mappedCsv.slice(1)"
                row-key="name"
            />
        </div>
        <q-card-section v-if="errors" class="flex justify-center items-center col-12">
            <q-markup-table flat bordered separator="cell">
                <thead>
                    <tr>
                        <th class="text-center">{{ t('name') }}</th>
                        <th class="text-center">{{t('table.validation_errors')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(error, name) in errors" :key="name">
                        <td class="text-center text-weight-bolder">{{ name }}</td>
                        <td class="text-center text-weight-bolder">
                            <q-markup-table flat bordered separator="cell">
                                <tbody>
                                    <tr v-for="(err, key) in error">
                                        <td class="text-weight-bolder">{{ key }}</td>
                                        <tr v-for="er in err">
                                            <td class="text-left text-negative  text-weight-bolder">{{ er }}</td>
                                        </tr>
                                    </tr>
                                </tbody>
                            </q-markup-table>
                        </td>
                    </tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
    </q-card>
</template>

<style>
input[type="file"] {
    width: 0;
    height: 0;
    overflow: hidden;
    opacity: 0;
}
table {
    width: 100%;
    margin-top: 20px;
}
select {
    width: 100%;
    padding: 10px;
}
</style>

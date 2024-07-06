<script setup>
import { useI18n } from "vue-i18n";
import { routeChildren } from "../../../utils/helpers";
import { computed } from "vue";
import { Dark } from "quasar";
import { useStore } from "vuex";

const peoples = computed(() =>
    routeChildren().filter((r) =>
        [
            "purchase.list",
            "purchaseReturn.list",
            "sale.list",
            "saleReturn.list",
        ].includes(r.name)
    )
);

const { t } = useI18n();
const getModuleName = (metaTitle) => {
    const [moduleName, moduleAction] = metaTitle.split(".");
    if (moduleAction === "list") {
        return moduleName;
    }
    return moduleAction;
};

const store = useStore();
const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);
</script>

<template>
    <div class="col-12">
        <div class="row q-col-gutter-md">
            <div
                class="col-12 col-lg-3 col-md-4 col-sm-6"
                v-for="(people, i) in peoples"
                :key="i"
                v-permission="[`${people.meta.permissions}`]"
            >
                <q-card
                    :class="{
                        'bg-white': !Dark.isActive,
                    }"
                    dark
                    flat
                >
                    <q-card-section class="text-center">
                        <div
                            class="text-h6"
                            :class="`text-${colorBasedOnMode}`"
                        >
                            {{
                                t(
                                    `links.${getModuleName(people.meta.title)}`,
                                    2
                                )
                            }}
                        </div>
                    </q-card-section>
                    <q-card-section class="text-center">
                        <q-icon
                            :color="`${colorBasedOnMode}`"
                            :name="people.meta.icon"
                            size="5em"
                        ></q-icon>
                    </q-card-section>
                    <q-card-actions align="center" class="q-pb-lg">
                        <q-btn
                            size="sm"
                            :label="$t('table.new_record')"
                            color="positive"
                            :to="{
                                name: `${getModuleName(
                                    people.meta.title
                                )}.create`,
                            }"
                            v-permission="[
                                `${`create-${getModuleName(
                                    people.meta.title
                                )}`}`,
                            ]"
                        />

                        <q-btn
                            size="sm"
                            :label="$t('action.list')"
                            color="indigo-10"
                            :to="{ name: people.name }"
                            v-permission="[
                                `list-${getModuleName(people.meta.title)}`,
                            ]"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from "vue-i18n";
import { routeChildren } from "../../../utils/helpers";
import { computed } from "vue";
import { Dark } from "quasar";
import { useStore } from "vuex";

const organizations = computed(() =>
    routeChildren().filter((r) =>
        [
            "company.list",
            "branch.list",
            "department.list",
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
                v-for="(organization, i) in organizations"
                :key="i"
                v-permission="[`${organization.meta.permissions}`]"
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
                                    `links.${getModuleName(organization.meta.title)}`,
                                    2
                                )
                            }}
                        </div>
                    </q-card-section>
                    <q-card-section class="text-center">
                        <q-icon
                            :color="`${colorBasedOnMode}`"
                            :name="organization.meta.icon"
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
                                    organization.meta.title
                                )}.create`,
                            }"
                            v-permission="[
                                `${`create-${getModuleName(
                                    organization.meta.title
                                )}`}`,
                            ]"
                        />

                        <q-btn
                            size="sm"
                            :label="$t('action.list')"
                            color="indigo-10"
                            :to="{ name: organization.name }"
                            v-permission="[
                                `list-${getModuleName(organization.meta.title)}`,
                            ]"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>
    </div>
</template>

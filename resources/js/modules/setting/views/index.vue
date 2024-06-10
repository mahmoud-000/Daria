<script setup>
import { useI18n } from "vue-i18n";
import { routeChildren } from "../../../utils/helpers";
import { computed } from "vue";
import { useStore } from "vuex";
import { Dark } from "quasar";

const settings = computed(() =>
    routeChildren().filter((r) =>
        [
            "setting.system",
            "setting.appearance",
            "role.list",
            "brand.list",
            "attribute.list",
            "category.list",
            "unit.list",
            "warehouse.list",
            "pipeline.list",
            "stage.list",
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
                v-for="(setting, i) in settings"
                :key="i"
                v-permission="[`${setting.meta.permissions}`]"
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
                                    `links.${getModuleName(
                                        setting.meta.title
                                    )}`,
                                    2
                                )
                            }}
                        </div>
                    </q-card-section>
                    <q-card-section class="text-center">
                        <q-icon
                            :color="`${colorBasedOnMode}`"
                            :name="setting.meta.icon"
                            size="5em"
                        ></q-icon>
                    </q-card-section>
                    <q-card-actions align="center" class="q-pb-lg">
                        <q-btn
                            size="sm"
                            :label="
                                [
                                    'setting.system',
                                    'setting.appearance',
                                ].includes(setting.name)
                                    ? $t('table.edit_record')
                                    : $t('table.new_record')
                            "
                            color="positive"
                            :to="{
                                name: [
                                    'setting.system',
                                    'setting.appearance',
                                ].includes(setting.name)
                                    ? setting.name
                                    : `${getModuleName(
                                          setting.meta.title
                                      )}.create`,
                            }"
                            v-permission="[
                                `${
                                    [
                                        'setting.system',
                                        'setting.appearance',
                                    ].includes(setting.name)
                                        ? setting.meta.permissions
                                        : `create-${getModuleName(
                                              setting.meta.title
                                          )}`
                                }`,
                            ]"
                        />

                        <q-btn
                            size="sm"
                            :label="$t('action.list')"
                            color="indigo-10"
                            :to="{ name: setting.name }"
                            v-if="
                                ![
                                    'setting.system',
                                    'setting.appearance',
                                ].includes(setting.meta.title)
                            "
                            v-permission="[
                                `list-${getModuleName(setting.meta.title)}`,
                            ]"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>
    </div>
</template>

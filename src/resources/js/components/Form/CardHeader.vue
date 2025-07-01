<script setup>
import { computed, toRefs } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";

const props = defineProps({
    reference: {
        type: String,
        required: true,
        default: () => "id",
    },
    subtitle: {
        type: String,
        required: false,
        default: () => null,
    },
    itemId: {
        type: Number,
        required: true,
        default: () => 0,
    },
});
const { reference, subtitle, itemId } = toRefs(props);

const route = useRoute();

const { t } = useI18n();

const routeTitle = route.meta.title;
const [moduleName, moduleAction] = routeTitle.split(".");

const actionTitle = t(`action.${moduleAction}`, {
    module: t(`links.${moduleName}`),
});

const formTitle = computed(() => {
    return !itemId.value ? actionTitle : `${actionTitle} ${reference.value}`;
});
</script>

<template>
    <q-card-section :class="!$q.dark.isActive ? 'bg-white' : 'bg-dark'">
        <div class="row flex-center">
            <div
                class="col-lg-6 col-md-6 col-xs-12 flex flex-center"
            >
                <div class="col-12">
                    <div class="text-h5">{{ formTitle }}</div>
                    <div class="text-subtitle2">{{ subtitle }}</div>
                </div>
            </div>

            <div
                class="col-lg-6 col-md-6 col-xs-12 flex flex-center"
            >
                <div class="col-12">
                    <q-btn
                        :label="t('action.cancel')"
                        color="negative"
                        :to="{ name: `${moduleName}.list` }"
                        class="q-mr-md"
                        v-permission="[`list-${moduleName}`]"
                    />
                    <q-btn
                        :label="t('action.save')"
                        type="submit"
                        color="positive"
                        @click="$emit('on-submit')"
                        v-permission="[
                            `${
                                !itemId ? `create-${moduleName}` : `edit-${moduleName}`
                            }`,
                        ]"
                    />
                </div>
            </div>
        </div>
    </q-card-section>
</template>

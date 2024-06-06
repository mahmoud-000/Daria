<script setup>
import { computed, toRefs } from "vue";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";

const props = defineProps({
    title: {
        type: String,
        required: true,
        default: () => "",
    },
});
const { title } = toRefs(props);

const { t } = useI18n();
const store = useStore();

const getTextClass = computed(() => store.getters["getTextClass"]);
</script>

<template>
    <q-card-section class="col-lg-12 col-md-12 col-xs-12 q-px-none q-pb-none">
        <div class="bg-primary flex flex-center col-12">
            <div class="col text-h5 q-pa-md" :class="getTextClass">
                {{ t(title) }}
            </div>
            <div class="col-1 q-pa-md">
                <slot name="btn" />
            </div>
        </div>

        <slot />
    </q-card-section>
</template>

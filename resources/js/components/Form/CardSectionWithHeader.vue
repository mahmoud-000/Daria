<script setup>
import { computed, toRefs, useSlots } from "vue";
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
const slots = useSlots()

const getTextClass = computed(() => store.getters["getTextClass"]);
</script>

<template>
    <q-card-section class="col-lg-12 col-md-12 col-xs-12 q-px-none q-pb-none">
        <q-expansion-item
            class="shadow-1 overflow-hidden"
            style="border-radius: 30px"
            icon="explore"
            :header-class="`bg-primary ${getTextClass}`"
            :expand-icon-class="getTextClass"
            default-opened
            expand-icon-toggle
        >
            <template v-slot:header>
                <q-item-section avatar v-if="slots.btn">
                    <slot name="btn" />
                </q-item-section>

                <q-item-section> {{ t(title) }} </q-item-section>
            </template>

            <slot />
        </q-expansion-item>
    </q-card-section>
</template>

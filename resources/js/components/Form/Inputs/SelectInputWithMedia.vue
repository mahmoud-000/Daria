<script setup>
import { useI18n } from "vue-i18n";
import { SelectInput } from "../../import";
import { ref, toRefs, computed } from "vue";
import { useStore } from "vuex";

const props = defineProps({
    inputModel: {
        type: String,
        default: () => "",
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const { t } = useI18n();
const store = useStore();

const getTextColor = computed(() => store.getters["getTextColor"]);
</script>

<template>
    <SelectInput
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        v-bind="$attrs"
        :errors="errors"
    >
        <template #option="scope">
            <q-item v-bind="scope.itemProps">
                <q-item-section avatar>
                    <q-avatar size="md">
                        <q-img :src="scope.opt.img" />
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ scope.opt.label }}</q-item-label>
                </q-item-section>
            </q-item>
        </template>

        <template #selected-item="scope">
            <q-chip
                :tabindex="scope.tabindex"
                color="primary"
                :text-color="getTextColor"
                class="q-ma-none"
            >
                <q-avatar size="sm">
                    <q-img :src="scope.opt.img" />
                </q-avatar>
                {{ scope.opt.label }}
            </q-chip>
        </template>
    </SelectInput>
</template>

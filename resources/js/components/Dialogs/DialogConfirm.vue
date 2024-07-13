<script setup>
import { ref } from "vue";
import { BaseBtn } from "../import";

defineProps({
    dialogConfirm: {
        type: Boolean,
        default: () => false,
    },
});

const maximizedToggle = ref(false);

const emit = defineEmits(["close-confirm", "delete-item"]);
</script>
<template>
    <q-dialog
        :modelValue="dialogConfirm"
        @update:modelValue="(value) => (dialogConfirm = value)"
        :maximized="maximizedToggle"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card style="min-width: 350px">
            <q-bar>
                <q-space />

                <q-btn
                    dense
                    flat
                    icon="fa-regular fa-window-minimize"
                    @click="maximizedToggle = false"
                    :disable="!maximizedToggle"
                >
                    <q-tooltip v-if="maximizedToggle">{{
                        $t("action.minimize")
                    }}</q-tooltip>
                </q-btn>
                <q-btn
                    dense
                    flat
                    icon="fa-regular fa-square"
                    @click="maximizedToggle = true"
                    :disable="maximizedToggle"
                >
                    <q-tooltip v-if="!maximizedToggle">{{
                        $t("action.maximize")
                    }}</q-tooltip>
                </q-btn>

                <q-btn
                    dense
                    flat
                    icon="fa-solid fa-xmark"
                    v-close-popup
                    @click="emit('close-confirm')"
                >
                    <q-tooltip>{{ $t("action.close") }}</q-tooltip>
                </q-btn>
            </q-bar>
            <q-card-section class="text-center">
                <q-icon name="fa-regular fa-trash-can" color="negative" size="4rem" />
                <h5>
                    <slot />
                </h5>
            </q-card-section>

            <q-card-actions align="right">
                <BaseBtn
                    :toolbar="$t('action.delete')"
                    @click="emit('delete-item')"
                    icon="fa-solid fa-check"
                    color="negative"
                    q-close-popup
                    size="md"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

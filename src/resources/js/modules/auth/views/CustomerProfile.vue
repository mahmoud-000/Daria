<script setup>
import { defineAsyncComponent } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/CustomerProfileForm.vue"));
</script>

<template>
    <Suspense>
        <template #default>
            <Form />
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>

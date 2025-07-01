<script setup>
import { Themes, TheSpinner } from "../../../components/import";
</script>

<template>
    <Suspense>
        <template #default>
            <Themes />
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>

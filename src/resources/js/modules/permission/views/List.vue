<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const config = reactive({
    moduleName: "user",
    fetchItems: "fetchUsers",
    getItems: "getUsers",
    destroyItem: "destroyUser",
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns" />
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>

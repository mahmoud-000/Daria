<script setup>
import { defineAsyncComponent, computed } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("region/fetchRegion", route.params.id);
const formData = computed(() => store.getters["region/getRegion"]);
</script>

<template>
  <Suspense>
    <template #default>
      <Form :form-data="formData" />
    </template>
    <template #fallback>
      <div class="fixed-center">
        <the-spinner />
      </div>
    </template>
  </Suspense>
</template>

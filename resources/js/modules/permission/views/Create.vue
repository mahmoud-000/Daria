<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
  username: "",
  email: "",
  firstname: "",
  lastname: "",
  gender: 0,
  is_active: 0,
});
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

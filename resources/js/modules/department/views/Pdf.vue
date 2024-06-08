<script setup>
import { defineAsyncComponent, computed } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("department/fetchDepartment", route.params.id);
const formData = computed(() => store.getters["department/getDepartment"]);

// import html2pdf from "html2pdf.js";
// let element = document.getElementById('wraooer-pdf');
// html2pdf(element);
</script>

<template>
    <Suspense>
        <template #default>
            <div id="wrapper-pdf" class="wrapper-pdf">
                <div style="width: 100%">
                    <div style="width: 80%; text-align: legt">
                        <span>التاريخ</span><br /><br />
                        <span>New Head</span><br /><br />
                        <span>Serial Number</span><br /><br />
                    </div>
                </div>

                <div style="width: 100%">
                    <div style="width: 80%; margin: auto; text-align: center">
                        <span
                            >Test Head Test Head Test Head Test Head Test Head
                            Test Head Test Head Test Head Test Head</span
                        ><br /><br />
                        <span
                            >Test Head Test Head Test Head Test Head Test Head
                            Test Head Test Head Test Head Test Head</span
                        ><br /><br />
                        <span>12222222 - 2222222222</span><br /><br />
                    </div>
                </div>

                <div style="width: 100%">
                    <div
                        style="
                            width: 80%;
                            margin: auto;
                            text-align: left;
                            border: 3px solid #000;
                            border-radius: 10px;
                        "
                    >
                        <span>Test Heading </span><br />
                    </div>
                    <div
                        style="
                            width: 80%;
                            margin: auto;
                            text-align: right;
                            border: 3px solid #000;
                            border-radius: 10px;
                        "
                    >
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                    </div>
                </div>

                <div style="width: 100%">
                    <div
                        style="
                            width: 80%;
                            margin: auto;
                            text-align: left;
                            border: 3px solid #000;
                            border-radius: 10px;
                        "
                    >
                        <span>Test Heading </span>
                    </div>
                    <div
                        style="
                            width: 80%;
                            margin: auto;
                            text-align: right;
                            border: 3px solid #000;
                            border-radius: 10px;
                        "
                    >
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                        <span>Name: </span><span>ddddddddd</span><br />
                    </div>
                </div>

                <div style="width: 100%">
                    <div style="width: 70%; margin: auto; text-align: left">
                        <span>Test Head Test Head </span>
                    </div>
                </div>
            </div>
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>

<style>
.wrapper-pdf {
    padding: 10px;
}

.pb-1 {
    padding-bottom: 10px;
}
</style>

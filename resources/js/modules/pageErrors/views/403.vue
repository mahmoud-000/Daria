<script setup>
import { useStore } from "vuex";
const store = useStore();
await store.dispatch("auth/fetchAuthPermissions");
</script>
<template>
    <div class="fixed-center text-center">
        <p>
            <q-icon name="warning" size="8rem" color="yellow"></q-icon>
        </p>
        <h3 class="text-faded">
            {{ $t("messages.403") }}<strong>(403)</strong>
        </h3>
        <q-btn
            color="secondary"
            :to="{ name: 'dashboard' }"
            :label="$t('messages.go_back_to_dashboard')"
        />
    </div>
</template>
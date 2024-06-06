<script setup>
import { Dark } from "quasar";
</script>

<template>
    <div class="fixed-center">
        <q-spinner-pie
            :color="Dark.isActive ? 'secondary' : 'primary'"
            size="10em"
        />
    </div>
</template>

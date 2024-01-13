<template>
    <div id="app-layout" class="container-fluid h-100 p-0 m-0">
        <AppHeader />

        <div class="m-4">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppHeader from "@/Layouts/AppLayout/AppHeader.vue";
import { ref, reactive, onMounted } from "vue";
import "../../scss/Layouts/appLayout.scss";

const props = defineProps<{}>();
</script>

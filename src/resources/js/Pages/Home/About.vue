<template>
    <LogoImage class="tb-card" dark-header>
        <hr class="my-4" />
        <p class="text-center">
            Tech Bench &copy; {{ app.copyright }}
            <span class="d-inline-block">
                Butcherman - All Rights Reserved
            </span>
        </p>
        <p class="text-center">{{ app.version }} (Build {{ build }})</p>
        <p class="text-center">Build Date - {{ build_date }}</p>
    </LogoImage>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import { useAppStore } from "@/Stores/AppStore";

defineProps<{
    build: string;
    build_date: string;
}>();

const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

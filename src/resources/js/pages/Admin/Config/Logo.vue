<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import LogoManagement from "@/features/administration/components/LogoManagement.vue";

defineProps<{
    currentLogo: string;
    isDefault: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <LogoManagement :current-logo :is-default />
</template>

<script setup lang="ts">
import LogoManagement from "@/features/admin/components/LogoManagement.vue";
import AppLayout from "@/layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    currentLogo: string;
    isDefault: boolean;
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="border-b border-slate-300">
            <h1>Application Logo</h1>
            <p>Update logo image to show your custom branding.</p>
        </div>
        <LogoManagement :current-logo :is-default />
    </div>
</template>

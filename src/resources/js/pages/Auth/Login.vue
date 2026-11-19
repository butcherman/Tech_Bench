<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginInstructions from "@/features/auth/components/LoginInstructions.vue";

defineOptions({ layout: AuthLayout });

const props = defineProps<{
    allowOath: boolean;
}>();
</script>

<template>
    <div class="w-96 p-4"><LoginInstructions :allow-oath /></div>
</template>

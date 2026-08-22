<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import MfaChallenge from "@/features/auth/components/MfaChallenge.vue";

const props = defineProps<{
    allowRemember: boolean;
    via: MultiFactorMethod;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <MfaChallenge v-bind="props" />
    </div>
</template>

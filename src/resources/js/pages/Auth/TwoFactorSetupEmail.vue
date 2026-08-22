<script setup lang="ts">
import MfaSetupEmail from "@/features/auth/components/MfaSetupEmail.vue";
import AppLayout from "@/layouts/AppLayout.vue";

const props = defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <MfaSetupEmail :allow-remember="allowRemember" />
    </div>
</template>

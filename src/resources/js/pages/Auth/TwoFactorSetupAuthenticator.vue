<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import MfaSetupAuthenticator from "@/features/auth/components/MfaSetupAuthenticator.vue";

const props = defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <MfaSetupAuthenticator :allow-remember="allowRemember" />
        </Card>
    </div>
</template>

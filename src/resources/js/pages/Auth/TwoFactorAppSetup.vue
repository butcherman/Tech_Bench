<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import AuthenticatorAppSetup from "@/features/auth/components/AuthenticatorAppSetup.vue";
import Card from "@/core/components/Card.vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <AuthenticatorAppSetup />
        </Card>
    </div>
</template>

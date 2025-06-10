<script setup lang="ts">
import AuthenticatorAppSetup from "@/Components/User/AuthenticatorAppSetup.vue";
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { onMounted, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";

const component = useTemplateRef("setup-component");

/**
 * Redirect to Dashboard after successful setup
 */
const goHome = () => {
    router.get(route("dashboard"));
};

onMounted(() => component.value?.triggerSetup());
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex justify-center items-center h-screen">
        <Card class="tb-card">
            <AuthenticatorAppSetup ref="setup-component" @success="goHome()" />
        </Card>
    </div>
</template>

<script setup lang="ts">
import AdminWatcher from "@/features/maintenance/components/AdminWatcher.vue";
import Card from "@/core/components/Card.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { dataGet } from "@/core/utilities/axiosWrapper";
import { index } from "@/wayfinder/routes/admin";
import { onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import { saveSetup } from "@/wayfinder/routes/init";

onMounted(() => {
    dataGet(saveSetup.url()).then((res) => {
        if (res) {
            router.get(index.url());
        }
    });
});
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="flex justify-center items-center h-screen px-4">
        <Card title="Completing Setup" size="md">
            <AdminWatcher />
        </Card>
    </div>
</template>

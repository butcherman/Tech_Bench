<template>
    <Head title="Dashboard" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <DashboardNotifications />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import DashboardNotifications from '@/Components/Home/DashboardNotifications.vue';
</script>

<script lang="ts">
export default { layout: App };
</script>

<template>
    <Head title="Dashboard" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <Notifications />
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import Notifications from '@/Components/Home/Notifications.vue';
</script>

<script lang="ts">
export default { layout: App };
</script>

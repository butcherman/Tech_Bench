<template>
    <Head title="Application Settings" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Configuration</div>
                        <ConfigForm :settings="settings" :tz_list="tz_list" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import ConfigForm from "@/Forms/Admin/ConfigForm.vue";

interface timezone {
    [key: string]: {
        [key: string]: string;
    };
}

defineProps<{
    settings: settings;
    tz_list: timezone;
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>

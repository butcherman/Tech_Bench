<template>
    <InitLayout :step-id="2">
        <div class="card-title">Basic Settings</div>
        <ConfigForm
            :settings="settings"
            :tz_list="tz_list"
            @step-completed="nextStep"
        />
    </InitLayout>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import InitLayout from "./InitLayout.vue";
import ConfigForm from "@/Forms/Admin/ConfigForm.vue";

interface timezoneType {
    [key: string]: {
        [key: string]: string;
    };
}

defineProps<{
    settings: settings;
    tz_list: timezoneType;
}>();

const nextStep = () => {
    router.get(route("init.step-3"));
};
</script>

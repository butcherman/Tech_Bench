<template>
    <div>
        <h5 class="text-center">Basic Settings</h5>
        <p class="text-center">
            Now we need some basic information about the site. Please enter the
            Full URL, the Timezone and the maximum filesize upload that will be
            allowed.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <SettingsForm
                    :settings="settings"
                    :tz-list="tzList"
                    @success="nextStep"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import SettingsForm from "@/Forms/Admin/Config/SettingsForm.vue";
import { router } from "@inertiajs/vue3";

interface timezoneType {
    [key: string]: {
        [key: string]: string;
    };
}

defineProps<{
    settings: appConfig;
    tzList: timezoneType;
}>();

const nextStep = () => {
    router.get(route("init.step-3"));
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <div class="text-center">
        <h1 class="font-bold text-2xl">Basic Settings</h1>
        <p>
            To start, we need some basic information about the site. Please
            enter the Full URL, the Timezone and the maximum filesize upload
            that will be allowed.
        </p>
        <BasicConfigForm
            class="w-full md:w-3/4 justify-self-center"
            :tz-list="timezoneList"
            :url="settings.url"
            :timezone="settings.timezone"
            :max-filesize="settings.max_filesize"
            :company_name="settings.company_name"
            init
            @success="router.get($route('init.step-2'))"
        />
    </div>
</template>

<script setup lang="ts">
import BasicConfigForm from "@/Forms/Admin/Config/BasicConfigForm.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    settings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
    };
    timezoneList: TimezoneList;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

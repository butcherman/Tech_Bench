<script setup lang="ts">
import BasicConfigForm from "@/Forms/Admin/Config/BasicConfigForm.vue";
import Card from "@/Components/_Base/Card.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    settings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
    };
    timezoneList: TimezoneList[];
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <Card class="tb-card" title="Basic Settings">
        <p class="text-center">
            To start, we need some basic information about the site. Please
            enter the Full URL, the Timezone and the maximum filesize upload
            that will be allowed.
        </p>
        <div>
            <BasicConfigForm
                :tz-list="timezoneList"
                :url="settings.url"
                :timezone="settings.timezone"
                :max-filesize="settings.max_filesize"
                :company_name="settings.company_name"
                init
                @success="router.get($route('init.step-2'))"
            />
        </div>
    </Card>
</template>

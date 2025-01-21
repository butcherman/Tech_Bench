<template>
    <Card class="tb-card" title="Tech Bench Settings">
        <BasicConfigForm
            :tz-list="timezoneList"
            :url="settings.url"
            :company_name="settings.company_name"
            :timezone="settings.timezone"
            :max-filesize="settings.max_filesize"
        />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import BasicConfigForm from "@/Forms/Admin/Config/BasicConfigForm.vue";

defineProps<{
    settings: {
        url: string;
        company_name: string;
        timezone: string;
        max_filesize: number;
    };
    timezoneList: TimezoneList[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

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
        welcome_message: string;
        home_links: { url: string; text: string }[];
    };
    timezoneList: TimezoneList[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Tech Bench Settings">
            <BasicConfigForm
                :tz-list="timezoneList"
                :url="settings.url"
                :company_name="settings.company_name"
                :timezone="settings.timezone"
                :max-filesize="settings.max_filesize"
                :welcome_message="settings.welcome_message"
                :home_links="settings.home_links"
            />
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import TechBenchConfigForm from "@/features/administration/forms/TechBenchConfigForm.vue";

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
        <Card size="md" title="Application Settings">
            <TechBenchConfigForm
                :settings="settings"
                :timezoneList="timezoneList"
            />
        </Card>
    </div>
</template>

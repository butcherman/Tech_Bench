<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import ApplicationConfigurationForm from "@/features/admin/forms/ApplicationConfigurationForm.vue";
import Card from "@/core/components/Card.vue";

defineOptions({ layout: AppLayout });

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

<template>
    <div class="flex flex-col gap-4">
        <div>
            <h1>Application Configuration</h1>
            <p>Application Global Settings</p>
        </div>
        <div class="flex justify-center">
            <Card size="md">
                <ApplicationConfigurationForm :settings :timezone-list />
            </Card>
        </div>
    </div>
</template>

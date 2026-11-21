<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import ApplicationConfigurationForm from "@/features/admin/forms/ApplicationConfigurationForm.vue";
import { useAppData } from "@/core/state/appData";

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

const { appName } = useAppData();
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1>Application Configuration</h1>
            <p class="text-muted">
                Manage your {{ appName }} application settings
            </p>
        </div>
        <div>
            <div class="md:flex justify-center">
                <ApplicationConfigurationForm
                    :settings
                    :timezone-list
                    class="md:w-3/4 xl:w-1/2"
                />
            </div>
        </div>
    </div>
</template>

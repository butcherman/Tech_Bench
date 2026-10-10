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
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-server" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Application Settings</h3>
                        <p class="text-muted">Basic Application Settings.</p>
                    </div>
                </div>
            </template>
            <TechBenchConfigForm
                :settings="settings"
                :timezoneList="timezoneList"
            />
        </Card>
    </div>
</template>

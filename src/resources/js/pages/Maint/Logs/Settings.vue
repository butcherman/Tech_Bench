<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import LogSettingsForm from "@/features/maintenance/forms/LogSettingsForm.vue";

const props = defineProps<{
    days: number;
    logLevel: string;
    levelList: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="sm" title="Log Settings">
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="cog" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Log Settings</h3>
                        <p class="text-muted">
                            Modify Log Levels and Retention.
                        </p>
                    </div>
                </div>
            </template>
            <LogSettingsForm
                :days="days"
                :log-level="logLevel"
                :level-list="levelList"
            />
        </Card>
    </div>
</template>

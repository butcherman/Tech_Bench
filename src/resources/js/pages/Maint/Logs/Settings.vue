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
            <LogSettingsForm
                :days="days"
                :log-level="logLevel"
                :level-list="levelList"
            />
        </Card>
    </div>
</template>

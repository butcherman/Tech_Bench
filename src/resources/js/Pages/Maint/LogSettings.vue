<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import LogSettingsForm from "@/Forms/Maintenance/LogSettingsForm.vue";

defineProps<{
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
        <Card class="tb-card-sm" title="Log Settings">
            <LogSettingsForm
                :days="days"
                :log-level="logLevel"
                :level-list="levelList"
            />
        </Card>
    </div>
</template>

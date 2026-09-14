<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import { computed } from "vue";
import { runBackup } from "@/wayfinder/routes/maint/backups/index.js";

const props = defineProps<{
    status: BackupStatus;
    nextRun: string;
    strategy: BackupStrategy;
}>();

const statusIcon = computed(() => (props.status.healthy ? "check" : "xmark"));
const statusColor = computed(() =>
    props.status.healthy ? "text-success" : "text-danger",
);
const statusText = computed(() =>
    props.status.healthy ? "Backups are Healthy" : props.status.message,
);
</script>

<template>
    <Card title="Backups">
        <template #append-title>
            <BaseButton
                :href="runBackup.url()"
                size="sm"
                text="Run Backup"
                pill
            />
        </template>
        <div class="border border-slate-300 rounded-lg p-3 flex flex-col gap-3">
            <div>
                <fa-icon :icon="statusIcon" :class="statusColor" />
                {{ statusText }}
            </div>
            <div class="grid grid-cols-2 gap-3 w-full lg:w-2/3">
                <div>Last Successful:</div>
                <div>{{ props.status.latest?.started_at }}</div>
                <div>Next Scheduled:</div>
                <div>{{ nextRun }}</div>
                <div>Retention Policy:</div>
                <div>
                    {{ strategy.daily }} Daily | {{ strategy.weekly }} Weekly |
                    {{ strategy.monthly }} Monthly |
                    {{ strategy.yearly }} Yearly
                </div>
            </div>
        </div>
    </Card>
</template>

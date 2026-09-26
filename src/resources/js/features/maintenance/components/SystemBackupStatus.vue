<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import Card from "@/core/components/Card.vue";
import { computed, ref } from "vue";
import { runBackup } from "@/wayfinder/routes/maint/backups/index.js";
import { useEcho } from "@laravel/echo-vue";

const props = defineProps<{
    status: BackupStatus;
    nextRun: string;
    strategy: BackupStrategy;
}>();

useEcho(
    "administration-channel",
    ".AdministrationEvent",
    (e: AdministrativeMessage) => {
        console.log(e);

        if (e.msg === "Backup completed!") {
            backupIsRunning.value = false;
        } else {
            backupIsRunning.value = true;
        }
    },
);

const backupIsRunning = ref(false);

const statusIcon = computed<string>(() =>
    props.status.healthy ? "check" : "xmark",
);
const statusColor = computed<string>(() =>
    props.status.healthy ? "text-success" : "text-danger",
);
const statusText = computed<string | null>(() =>
    props.status.healthy ? "Backups are Healthy" : props.status.message,
);
</script>

<template>
    <Card title="Backups">
        <template #append-title>
            <BaseBadge
                v-if="!backupIsRunning"
                :href="runBackup.url()"
                size="sm"
                text="Run Backup"
            />
            <BaseBadge v-else>
                <fa-icon
                    icon="spinner"
                    class="fa-spin"
                    v-tooltip="'Backup Running'"
                />
            </BaseBadge>
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

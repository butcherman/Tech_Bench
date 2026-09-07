<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import { useLogEntryHelper } from "../composables/logEntryHelper";
import { computed } from "vue";

const { getBadgeClass, getBadgeIcon } = useLogEntryHelper();

const props = defineProps<{
    activeEntry: LogEntry;
}>();

const entryTimestamp = computed(() => {
    console.log(props.activeEntry);
    let date = new Date(props.activeEntry.timestamp);

    return {
        dateStamp: date.toLocaleDateString("en-us", {
            year: "numeric",
            month: "long",
            day: "numeric",
        }),
        timeStamp: date.toLocaleTimeString(),
    };
});
</script>

<template>
    <div class="flex flex-col gap-2 max-w-96">
        <div>
            <BaseBadge
                variant="none"
                class="uppercase"
                :text="activeEntry.level"
                :icon="getBadgeIcon(activeEntry.level)"
                :class="getBadgeClass(activeEntry.level)"
            />
        </div>
        <div>
            {{ entryTimestamp.dateStamp }} - {{ entryTimestamp.timeStamp }}
        </div>

        <div class="border border-slate-300 rounded-lg p-2">
            <h5 class="text-muted">Message:</h5>
            <div class="p-2 overflow-x-auto">
                {{ activeEntry.data.body }}
            </div>
        </div>

        <div
            v-if="activeEntry.data.context"
            class="border border-slate-300 rounded-lg p-2"
        >
            {{ activeEntry.data.context }}
            <h6 class="text-muted">Trace ID:</h6>
            <div class="px-2">
                {{ activeEntry.data.context.trace_id }}
            </div>
            <h6 class="text-muted">User:</h6>
            <div class="px-2">
                <div>{{ activeEntry.data.context.user?.full_name }}</div>
                <div>{{ activeEntry.data.context.user?.email }}</div>
            </div>
            <h6 class="text-muted">IP Address:</h6>
            <div class="px-2">
                {{ activeEntry.data.context.ip_address }}
            </div>
        </div>

        <div v-if="activeEntry.data.extra">
            {{ activeEntry.data.extra }}
        </div>

        <div
            v-if="activeEntry.data.stack_trace.length"
            class="border border-slate-300 rounded-lg p-2"
        >
            <h6 class="text-muted">Stack Trace:</h6>
            <div class="px-2 overflow-x-auto text-nowrap">
                <!-- {{ activeEntry.data.stack_trace }} -->
                <div v-for="line in activeEntry.data.stack_trace">
                    <span v-if="line !== '[stacktrace]'">
                        {{ line }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

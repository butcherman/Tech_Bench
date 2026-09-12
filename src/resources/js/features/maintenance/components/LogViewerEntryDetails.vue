<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import TableStacked from "@/core/features/dataResources/TableStacked.vue";
import { useLogEntryHelper } from "../composables/logEntryHelper";
import { computed } from "vue";

const emit = defineEmits<{
    search: [string];
}>();

const props = defineProps<{
    activeEntry: LogEntry;
}>();

const { getBadgeClass, getBadgeIcon } = useLogEntryHelper();

const entryTimestamp = computed<{ dateStamp: string; timeStamp: string }>(
    () => {
        let date = new Date(props.activeEntry.timestamp);

        return {
            dateStamp: date.toLocaleDateString("en-us", {
                year: "numeric",
                month: "long",
                day: "numeric",
            }),
            timeStamp: date.toLocaleTimeString(),
        };
    },
);

const isValueObject = (rowData: object | number | string): boolean => {
    if (typeof rowData === "string" || typeof rowData === "number") {
        return false;
    }

    return true;
};

const onSearchData = (searchQuery: string) => {
    emit("search", searchQuery);
};
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
            <h6 class="text-muted">Message:</h6>
            <div class="px-2 overflow-x-auto">
                {{ activeEntry.data.body }}
            </div>
        </div>

        <div
            v-if="activeEntry.data.context"
            class="border border-slate-300 rounded-lg p-2"
        >
            <h6 class="text-muted">Trace ID:</h6>
            <div
                v-tooltip="'Search this Trace ID'"
                class="px-2 text-blue-700 pointer"
                @click="onSearchData(activeEntry.data.context.trace_id)"
            >
                {{ activeEntry.data.context.trace_id }}
            </div>
            <div v-if="activeEntry.data.context.user">
                <h6 class="text-muted">User:</h6>
                <div
                    v-tooltip="'Trace this user'"
                    class="px-2 text-blue-700 pointer"
                    @click="onSearchData(activeEntry.data.context.user)"
                >
                    <div>{{ activeEntry.data.context.user }}</div>
                    <div>User ID: {{ activeEntry.data.context.user_id }}</div>
                </div>
            </div>
            <div v-if="activeEntry.data.context.ip_address">
                <h6 class="text-muted">IP Address:</h6>
                <div
                    v-tooltip="'Trace this IP Address'"
                    class="px-2 text-blue-700 pointer"
                    @click="onSearchData(activeEntry.data.context.ip_address)"
                >
                    {{ activeEntry.data.context.ip_address }}
                </div>
            </div>
        </div>

        <div
            v-if="activeEntry.data.extra"
            class="border border-slate-300 rounded-lg p-2 overflow-x-auto"
        >
            <h6 class="text-muted">Additional Data:</h6>
            <TableStacked :data="activeEntry.data.extra" compact>
                <template #row="{ rowData }">
                    <template v-if="isValueObject(rowData.value)">
                        <td colspan="2">
                            <div class="font-bold text-muted">
                                {{ rowData.toTitle }} :
                            </div>
                            <TableStacked :data="rowData.value" compact>
                                <template #index="{ rowData }">
                                    <div
                                        class="text-start text-muted flex flex-row gap-1 text-nowrap"
                                    >
                                        <div class="grow">
                                            {{ rowData.toTitle }}
                                        </div>
                                        <div>:</div>
                                    </div>
                                </template>
                            </TableStacked>
                        </td>
                    </template>
                    <template v-else>
                        <th
                            class="text-start text-muted flex flex-row gap-1 pe-2 text-nowrap"
                        >
                            <div class="grow">
                                {{ rowData.toTitle }}
                            </div>
                            <div>:</div>
                        </th>
                        <td class="text-nowrap">{{ rowData.value }}</td>
                    </template>
                </template>
            </TableStacked>
        </div>

        <div
            v-if="activeEntry.data.stack_trace.length"
            class="border border-slate-300 rounded-lg p-2 overflow-x-auto"
        >
            <h6 class="text-muted">Stack Trace:</h6>
            <div class="px-2 overflow-x-auto text-nowrap">
                <div v-for="line in activeEntry.data.stack_trace">
                    <span v-if="line !== '[stacktrace]'">
                        {{ line }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

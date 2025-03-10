<template>
    <div>
        <Head title="Application Logs" />
        <div class="row justify-content-center">
            <div v-for="channel in channels" class="col">
                <Link
                    :href="$route('maint.logs.show', channel)"
                    class="btn btn-info w-100"
                >
                    {{ channel }}
                </Link>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Files</div>
                        <h5 v-if="!channel" class="text-center">
                            Select A Channel Above To See Log Files
                        </h5>
                        <Table
                            v-else
                            :columns="tableCols"
                            :rows="logList"
                            row-clickable
                            no-results-text="No Log Files Found For This Channel"
                            @on-row-click="onRowClick"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

interface logList {
    filename: string;
    [key: string]: number | string;
}

const props = defineProps<{
    channels: string[];
    levels: logLevel[];
    logList: logList[];
    channel: string | null;
    channelType: string | null;
}>();

const tableCols = ref<tableColumn[]>([
    {
        label: "File Name",
        field: "filename",
        sort: true,
        filterOptions: {
            enabled: true,
        },
    },
    {
        label: "Total Entries",
        field: "total",
        sort: true,
    },
]);

onMounted(() => {
    if (props.channelType === "app") {
        props.levels.forEach((level) => {
            tableCols.value.push({
                label: level.name,
                field: level.name,
                icon: level.icon,
                textVariant: level.color,
                sort: true,
            });
        });
    }
});

const onRowClick = (rowData: logList) => {
    router.get(route("maint.logs.view", [props.channel, rowData.filename]));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

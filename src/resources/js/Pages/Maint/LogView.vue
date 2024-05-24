<template>
    <div>
        <Head title="Log Details" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Stats</div>
                        <Table
                            :columns="statCols"
                            :rows="fileStats"
                            responsive
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <RefreshButton :only="['file-entries']" />
                            {{ filename }}
                            <a
                                :href="
                                    $route('maint.logs.download', [
                                        channel,
                                        filename,
                                    ])
                                "
                                class="float-end"
                                title="Download Log File"
                                v-tooltip
                            >
                                <fa-icon icon="download" />
                            </a>
                        </div>
                        <Table
                            :columns="logCols"
                            :rows="fileEntries"
                            responsive
                        >
                            <template #action="{ rowData }">
                                <span
                                    v-if="rowData.stack_trace"
                                    class="pointer badge bg-danger"
                                    title="Show Stack Trace"
                                    v-tooltip
                                    @click="showDetails(rowData)"
                                >
                                    <fa-icon icon="exclamation-circle" />
                                </span>
                                <span
                                    v-else-if="rowData.details"
                                    class="pointer badge bg-info"
                                    title="Show Additional Details"
                                    v-tooltip
                                    @click="showDetails(rowData)"
                                >
                                    <fa-icon icon="eye" />
                                </span>
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
        <Modal
            ref="detailsModal"
            title="Additional Information"
            size="xl"
            @hidden="activeDetails = null"
        >
            <pre>{{ activeDetails }}</pre>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import Modal from "@/Components/_Base/Modal.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { ref, onMounted } from "vue";

const props = defineProps<{
    levels: logLevel[];
    channel: string;
    filename: string;
    fileStats: { [key: string]: number }[];
    fileEntries: logLine[];
}>();

const detailsModal = ref<InstanceType<typeof Modal> | null>(null);
const activeDetails = ref<string[] | null>(null);

const showDetails = (rowData: logLine) => {
    if (rowData.stack_trace) {
        activeDetails.value = rowData.stack_trace;
    } else {
        activeDetails.value = rowData.details;
    }
    detailsModal.value?.show();
};

const statCols = ref<tableColumn[]>([
    {
        label: "Total Entries",
        field: "total",
    },
]);

const logCols = ref([
    {
        label: "Time",
        field: "time",
    },
    {
        label: "Level",
        field: "level",
    },
    {
        label: "Message",
        field: "message",
    },
]);

onMounted(() => {
    props.levels.forEach((level) => {
        statCols.value.push({
            label: level.name,
            field: level.name,
            icon: level.icon,
            textVariant: level.color,
        });
    });
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

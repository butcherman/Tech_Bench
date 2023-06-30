<template>
    <div>
        <Head title="Log Details" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Stats</div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <LogStatsHeader :levels="levels" />
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ fileStats.total }}
                                        </td>
                                        <template
                                            v-for="level in levels"
                                            :key="level.name"
                                        >
                                            <td>
                                                {{
                                                    fileStats[
                                                        level.name.toLocaleLowerCase() as keyof typeof fileStats
                                                    ]
                                                }}
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <Overlay :loading="loading">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                Log Details
                                <RefreshButton
                                    class="float-end"
                                    :only="['file-stats', 'log-file']"
                                    @start="loading = true"
                                    @end="loading = false"
                                />
                                <a
                                    :href="$route('admin.logs.download', [channel, fileName]).toString()"
                                    class="float-end text-primary"
                                    title="Download Log File"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-download" />
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Level</th>
                                            <th>Message</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            v-for="(row, index) in logFile"
                                            :key="index"
                                        >
                                            <tr>
                                                <td>{{ row.time }}</td>
                                                <td>
                                                    {{ row.level }}
                                                    <fa-icon
                                                        :icon="
                                                            findLogLevelIcon(
                                                                row.level
                                                            )
                                                        "
                                                        :class="
                                                            findLogLevelColor(
                                                                row.level
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>{{ row.message }}</td>
                                                <td>
                                                    <span
                                                        v-if="row.stack_trace"
                                                        class="pointer badge rounded-pill bg-danger"
                                                        title="View Stack Trace Data"
                                                        data-bs-toggle="collapse"
                                                        :data-bs-target="`#expand-${index}`"
                                                        v-tooltip
                                                    >
                                                        <fa-icon
                                                            icon="fa-exclamation-circle"
                                                        />
                                                    </span>
                                                    <span
                                                        v-else-if="row.details"
                                                        class="pointer badge rounded-pill bg-info"
                                                        title="Show Details"
                                                        data-bs-toggle="collapse"
                                                        :data-bs-target="`#expand-${index}`"
                                                        v-tooltip
                                                    >
                                                        <fa-icon
                                                            icon="fa-eye"
                                                        />
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    row.stack_trace ||
                                                    row.details
                                                "
                                                :id="`expand-${index}`"
                                                class="collapse"
                                            >
                                                <td colspan="4">
                                                    <div
                                                        class="table table-responsive px-4"
                                                    >
                                                        <table class="table">
                                                            <tbody>
                                                                <template
                                                                    v-if="
                                                                        row.stack_trace
                                                                    "
                                                                >
                                                                    <tr
                                                                        v-for="line in row.stack_trace"
                                                                    >
                                                                        <td>
                                                                            {{
                                                                                line
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                                <template
                                                                    v-if="
                                                                        row.details
                                                                    "
                                                                >
                                                                    <tr
                                                                        v-for="(
                                                                            data,
                                                                            key
                                                                        ) in row.details"
                                                                    >
                                                                        <th
                                                                            class="text-end"
                                                                        >
                                                                            {{
                                                                                key
                                                                            }}:
                                                                        </th>
                                                                        <td>
                                                                            {{
                                                                                data
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </Overlay>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import LogStatsHeader from "@/Components/Logging/LogStatsHeader.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import { ref } from "vue";

const props = defineProps<{
    levels: logLevels[];
    channel: string;
    fileStats: logStats;
    logFile: any[];
    fileName: string;
}>();

const loading = ref(false);

const findLogLevelIcon = (level: string) => {
    return props.levels.filter(
        (item) => item.name.toLowerCase() === level.toLowerCase()
    )[0].icon;
};

const findLogLevelColor = (level: string) => {
    return props.levels.filter(
        (item) => item.name.toLowerCase() === level.toLowerCase()
    )[0].color;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

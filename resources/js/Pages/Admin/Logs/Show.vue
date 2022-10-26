<template>
    <App>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Stats</div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Total Entries</th>
                                    <th v-for="level in levels">
                                        <fa-icon :icon="level.icon" />
                                        {{ level.name }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ filename }}</td>
                                    <td>{{ stats.total }}</td>
                                    <td v-for="level in levels">
                                        {{ stats[level.name.toLowerCase()] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Log Details
                            <Link
                                :href="route('admin.logs.download', [props.channel, props.filename])"
                                class="float-end text-primary"
                                title="Download Log File"
                                v-tooltip
                            >
                                <fa-icon icon="fa-download" />
                            </Link>
                        </div>
                        <div id="table-wrapper" class="table-responsive">
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
                                    <template v-for="(row, index) in logFile" :key="index">
                                        <tr>
                                            <td>{{ row.time }}</td>
                                            <th>{{ row.level }}</th>
                                            <td>{{ row.message }}</td>
                                            <td>
                                                <span
                                                    v-if="row.stack_trace"
                                                    class="pointer badge rounded-pill bg-danger"
                                                    title="View Stack Trace Data"
                                                    data-bs-toggle="collapse"
                                                    :data-bs-target="`#stack-trace-${index}`"
                                                    v-tooltip
                                                >
                                                    <fa-icon icon="fa-exclamation-circle" />
                                                </span>
                                                <span
                                                    v-else-if="row.details"
                                                    class="pointer badge rounded-pill bg-info"
                                                    title="Show Details"
                                                    data-bs-toggle="collapse"
                                                    :data-bs-target="`#details-${index}`"
                                                    v-tooltip
                                                >
                                                    <fa-icon icon="fa-eye" />
                                                </span>

                                            </td>
                                        </tr>
                                        <tr
                                            v-if="row.stack_trace"
                                            :id="`stack-trace-${index}`"
                                            class="collapse"
                                        >
                                            <td colspan="4">
                                                <div class="table-responsive">
                                                    <table class="table table-warning">
                                                        <tbody>
                                                            <tr v-for="line in row.stack_trace">
                                                                <td>{{ line }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="row.details"
                                            :id="`details-${index}`"
                                            class="collapse"
                                        >
                                            <td colspan="4">
                                                <dl class="row">
                                                    <template v-for="(data, key) in row.details">
                                                        <dt class="col-4 text-end border">{{ key }}:</dt>
                                                        <dt class="col-8">{{ data }}</dt>
                                                    </template>
                                                </dl>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App           from '@/Layouts/app.vue';
    import type { levelsType,
        logFilesType,
        logDetailsType } from '@/Types';

    const props = defineProps<{
        channel : string;
        levels  : levelsType[];
        filename: string;
        stats   : logFilesType;
        logFile : logDetailsType;
    }>();
</script>

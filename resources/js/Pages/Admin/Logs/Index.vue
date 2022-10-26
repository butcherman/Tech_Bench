<template>
    <Head title="Logs" />
    <App>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Channels</div>
                        <div class="row justify-content-center">
                            <div
                                v-for="channel in channels"
                                :key="channel"
                                class="col-lg-2"
                            >
                                <Link
                                    :href="route('admin.logs.channels', channel)"
                                    type="button"
                                    class="btn btn-primary w-100 btn-pill"
                                >
                                    {{ channel }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Files</div>
                        <p v-if="logFiles === undefined" class="text-center">Select A Log Channel to View the Logs</p>
                        <p v-else-if="logFiles.length === 0" class="text-center">No Logs Available for this Channel</p>
                        <div v-else>
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
                                    <tr
                                        v-for="log in logFiles"
                                        class="pointer"
                                        @click="goToRow(log)"
                                    >
                                        <td>{{ log.filename }}</td>
                                        <td>{{ log.total }}</td>
                                        <td v-for="level in levels">
                                            {{ log[level.name.toLowerCase()] }}
                                        </td>
                                    </tr>
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
    import App                               from '@/Layouts/app.vue';
    import { Inertia }                       from '@inertiajs/inertia';
    import type { levelsType, logFilesType } from '@/Types';

    defineProps<{
        channels : string[];
        levels  ?: levelsType[];
        logFiles?: logFilesType[];
    }>();

    const goToRow = (logData:logFilesType) => {
        Inertia.get(route('admin.logs.show', [route().params.channel, logData.filename]));
    }
</script>

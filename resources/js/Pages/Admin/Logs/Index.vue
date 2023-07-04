<template>
    <div>
        <Head title="App Logs" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Channels</div>
                        <div class="row justify-content-center">
                            <div
                                v-for="channel in channels"
                                :key="channel"
                                class="col-lg-3 col-md-6"
                            >
                                <Link
                                    as="button"
                                    :href="
                                        $route('admin.logs.channel', channel)
                                    "
                                    class="btn btn-info w-100 btn-pill my-2"
                                >
                                    {{ channel }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="logList && channel" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Files</div>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-hover table-bordered"
                            >
                                <LogStatsHeader :levels="levels" has-filename />
                                <tbody>
                                    <template
                                        v-for="log in logList"
                                        :key="log.filename"
                                    >
                                        <tr class="row-link">
                                            <td>
                                                <Link
                                                    :href="
                                                        $route(
                                                            'admin.logs.view',
                                                            [
                                                                channel,
                                                                log.filename,
                                                            ]
                                                        )
                                                    "
                                                >
                                                    {{ log.filename }}
                                                </Link>
                                            </td>
                                            <td>
                                                <Link
                                                    class="text-end pe-3"
                                                    :href="
                                                        $route(
                                                            'admin.logs.view',
                                                            [
                                                                channel,
                                                                log.filename,
                                                            ]
                                                        )
                                                    "
                                                >
                                                    {{ log.total }}
                                                </Link>
                                            </td>
                                            <template
                                                v-for="level in levels"
                                                :key="level.name"
                                            >
                                                <td>
                                                    <Link
                                                        class="text-end pe-3"
                                                        :href="
                                                            $route(
                                                                'admin.logs.view',
                                                                [
                                                                    channel,
                                                                    log.filename,
                                                                ]
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            log[
                                                                level.name.toLocaleLowerCase() as keyof typeof log
                                                            ]
                                                        }}
                                                    </Link>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import LogStatsHeader from "@/Components/Logging/LogStatsHeader.vue";

defineProps<{
    channels: string[];
    levels: logLevels[];
    channel?: string;
    logList?: logStats[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

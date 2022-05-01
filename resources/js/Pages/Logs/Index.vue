<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Application Logs</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Channels</div>
                        <div class="row justify-content-center">
                            <div class="col-lg-2" v-for="channel in channels" :key="channel">
                                <inertia-link as="b-button" :href="route('admin.logs.channel', channel)" variant="info" class="m-2" block pill>{{channel}}</inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Details</div>
                        <h4 v-if="!dirty" class="text-center">Select a Log Channel to list the logs</h4>
                        <b-table v-else striped responsive :fields="fields" :items="log_files" empty-text="No Log Files Found" show-empty>
                            <template #cell(filename)="data">
                                <inertia-link :href="route('admin.logs.view', [channel, data.item.filename])">{{data.item.filename}}</inertia-link>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body text-center">
                        <inertia-link as="b-button" :href="route('admin.logs.settings')" pill variant="info">Change Log Settings</inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * List of all possible log channels
             */
            channels: {
                type:     Array,
                required: true,
            },
            /**
             * Array list of log files available for the selected channel
             */
            log_files: {
                type:     Array,
                default:  null,
            },
            /**
             * List of all possible log levels
             */
            levels: {
                type:     Array,
                default:  null,
            },
            /**
             * Selected log channel
             */
            channel: {
                type:     String,
                default:  null,
            }
        },
        computed: {
            dirty()
            {
                return this.log_files !== null;
            },
            fields()
            {
                var fieldArr = [
                    {
                        key:     'filename',
                        label:   'Filename',
                        sortable: true,
                    },
                    {
                        key:     'total',
                        label:   'Entries',
                        sortable: true,
                    }
                ];

                this.levels.forEach(el => {
                    fieldArr.push({
                        key:      el.name.toLowerCase(),
                        label:    el.name,
                        sortable: true,
                    });
                });

                return fieldArr;
            }
        }
    }
</script>

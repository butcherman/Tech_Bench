<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">{{channel.name}}</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log File stats</div>
                        <b-table striped responsive :fields="statFields" :items="stats" empty-text="Log File Not Found" show-empty></b-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            {{filename}}
                            <b-badge class="float-right" :href="route('admin.logs.download', [channel.name, filename])" pill variant="info" title="Download Log" v-b-tooltip.hover><i class="fas fa-download"></i></b-badge>
                        </div>
                        <b-table striped responsive :fields="logFields" :items="log_file" empty-text="Log File Not Found" show-empty>
                            <template #cell(data)="data">
                                <b-button v-if="data.item.details && data.item.details.length > 0" @click="data.toggleDetails" variant="info" size="sm" pill>
                                    <i class="fas" :class="data.detailsShowing ? 'fa-eye-slash' : 'fa-eye'" :title="data.detailsShowing ? 'Hide Details' : 'Show Details'" v-b-tooltip.hover></i>
                                </b-button>
                                <b-button v-else-if="data.item.stack_trace" @click="data.toggleDetails" variant="info" size="sm" >
                                    Stack Trace
                                </b-button>
                            </template>
                            <template #row-details="data">
                                <b-table v-if="data.item.details && data.item.details.length > 0" small stacked :items="data.item.details"></b-table>
                                <div v-else-if="data.item.stack_trace">
                                    <div v-for="line in data.item.stack_trace" :key="line">
                                        {{line}}
                                    </div>
                                </div>
                            </template>
                        </b-table>
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
             * List of available log levels
             */
            levels: {
                type:     Array,
                required: true,
            },
            /**
             * Selected log channel
             */
            channel: {
                type:     Object,
                required: true,
            },
            /**
             * Selected filename of log file
             */
            filename: {
                type:     String,
                required: true,
            },
            /**
             * Array of statistics for each occurance of log level type
             */
            stats: {
                type:     Array,
                required: true,
            },
            /**
             * Array version of log file, each line is an array item
             */
            log_file: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                logFields: [
                    {
                        key:     'time',
                        label:   'Time',
                        sortable: true,
                    },
                    {
                        key:     'level',
                        label:   'Level',
                        sortable: true,
                    },
                    {
                        key:     'message',
                        label:   'Message',
                        sortable: false,
                    },
                    {
                        key:     'data',
                        label:   'Details',
                        sortable: false,
                    },
                ]
            }
        },
        computed: {
            statFields()
            {
                var fieldArr = [
                    {
                        key:     'filename',
                        label:   'Filename',
                        sortable: false,
                    },
                    {
                        key:     'total',
                        label:   'Entries',
                        sortable: false,
                    }
                ];

                this.levels.forEach(el => {
                    fieldArr.push({
                        key:      el.name.toLowerCase(),
                        label:    el.name,
                        sortable: false,
                    });
                });

                return fieldArr;
            },

        },
    }
</script>

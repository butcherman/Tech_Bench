<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">User Login Report</h4>
            </div>
        </div>
        <div class="row">
            <div class="col grid-margin">
                <div class="card">
                    <div class="card-body">
                        <b-list-group>
                            <b-list-group-item v-for="user, name in data" :key="name">
                                <h4>{{name}}<b-badge class="float-right">Logins - {{user.totals}}</b-badge></h4>
                                <div v-if="user.totals == 0">
                                    <h5 class="text-center">No Data</h5>
                                </div>
                                <b-table-simple responsive v-else class="mx-4">
                                    <b-thead>
                                        <b-tr>
                                            <b-th>Date</b-th>
                                            <b-th>Time</b-th>
                                            <b-th>IP Address</b-th>
                                        </b-tr>
                                    </b-thead>
                                    <b-tbody>
                                        <b-tr v-for="(d, date) in user.data" :key="date">
                                            <b-td>{{date}}</b-td>
                                            <b-td>
                                                <div v-for="t in d" :key="t">
                                                    {{t.time}}
                                                </div>
                                            </b-td>
                                            <b-td>
                                                <div v-for="i in d" :key="i">
                                                    {{i.ip}}
                                                </div>
                                            </b-td>
                                        </b-tr>
                                    </b-tbody>
                                </b-table-simple>
                            </b-list-group-item>
                        </b-list-group>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                //

            }
        },
        created() {
            //
        },
        mounted() {
             //
            //  console.log(this.data);
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //
        }
    }
</script>

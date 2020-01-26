<template>
    <div>
        <div v-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Gathering Modules</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <vue-good-table v-else
            ref="backup-list-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Active Modules</h4>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'actions'">
                    <i v-if="data.row.status === 'Enabled'" class="far fa-bell-slash pointer" title="Disable" v-b-tooltip @click="disableModule(data.row.name)"></i>
                    <i v-else class="far fa-bell pointer" title="Enable" v-b-tooltip @click="enableModule(data.row.name)"></i>
                    <!-- <i class="far fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteModule(data.row)"></i> -->
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    export default {
        props: [
            //
        ],
        data() {
            return {
                loading: true,
                error: false,
                table: {
                    columns: [
                        {
                            label: 'Name:',
                            field: 'name',
                        },
                        // {
                        //     label: 'Version',
                        //     field: 'version',
                        // },
                        {
                            label: 'Status:',
                            field: 'status',
                        },
                        {
                            label: 'Actions:',
                            field: 'actions',
                        }
                    ],
                    rows: [],
                }
            }
        },
        created() {
            //
            this.getModules();
        },
        methods: {
            //
            getModules()
            {
                axios.get(this.route('admin.module.getEnabled', 'active'))
                    .then(res => {
                        console.log(res);
                        this.loading = false;
                        this.table.rows = res.data;
                    }).catch(error => this.error = true);
            },
            disableModule(name)
            {
                console.log(name);
                axios.get(this.route('admin.module.disable', name))
                    .then(res => {
                        console.log(res);
                        location.reload();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            enableModule(name)
            {
                axios.get(this.route('admin.module.enable', name))
                    .then(res => {
                        console.log(res);
                        location.reload();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            deleteModule(name)
            {
                console.log(name);
            }
        }

    }
</script>

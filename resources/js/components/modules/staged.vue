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
                <h4 class="text-center">No Staged Modules</h4>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'actions'">
                    <i class="fas fa-broadcast-tower pointer" title="Activate" v-b-tooltip @click="activateModule(data.row.name)"></i>
                    <i class="far fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteModule(data.row.name)"></i>
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
                axios.get(this.route('admin.module.getStaged'))
                    .then(res => {
                        console.log(res);
                        this.loading = false;
                        this.table.rows = res.data;
                    }).catch(error => this.error = true);
            },
            activateModule(name)
            {
                console.log(name);
                axios.get(this.route('admin.module.activate', name))
                    .then(res => {
                        console.log(res);
                        location.reload();
                        console.log('good to go');
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            deleteModule(name)
            {
                axios.delete(this.route('admin.module.deleteStaged', name))
                    .then(res => {
                        console.log(res);
                        location.reload();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        }
    }
</script>

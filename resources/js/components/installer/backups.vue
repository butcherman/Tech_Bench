<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-button block variant="primary" :disabled="backupButton.disable" @click="runBackup">
                            <span class="spinner-border spinner-border-sm text-danger" v-show="backupButton.disable"></span>
                            {{backupButton.text}}
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div v-if="error">
                            <h5 class="text-center">Problem Loading Data...</h5>
                        </div>
                        <div v-else-if="loading">
                            <h5 class="text-center">Gathering Backups</h5>
                            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                        </div>
                        <vue-good-table
                            v-else
                            ref="backup-list-table"
                            styleClass="vgt-table bordered w-100"
                            :columns="table.columns"
                            :rows="table.rows"
                        >
                            <div slot="emptystate">
                                <h4 class="text-center">No Backups</h4>
                            </div>
                            <template slot="table-row" slot-scope="data">
                                <span v-if="data.column.field == 'actions'">
                                    <a :href="route('admin.downloadBackup', data.row.name)" title="Download" v-b-tooltip><i class="fas fa-cloud-download-alt"></i></a>
                                    <i class="far fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteBackup(data.row.name)"></i>
                                </span>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        To recover a backup, please view the instructions at <a href="https://tech-bench.readthedocs.io/en/latest/admin/index.html">https://tech-bench.readthedocs.io/en/latest/admin/index.html</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [

    ],
    data() {
        return {
            error: false,
            loading: true,
            backupButton: {
                text: 'Run Backup',
                disable: false,
            },
            table: {
                columns: [
                    {
                        label: 'Name',
                        field: 'name',
                    },
                    {
                        label: 'Date',
                        field: 'date',
                    },
                    {
                        label: 'Actions',
                        field: 'actions',
                    }
                ],
                rows: [],
            }
        }
    },
    created()
    {
        this.getBackups();
    },
    methods: {
        getBackups()
        {
            axios.get(this.route('admin.getBackups'))
                .then(res => {
                    this.table.rows = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },
        deleteBackup(name)
        {
            axios.get(this.route('admin.delBackup', name))
                .then(res => {
                    this.getBackups();
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
        },
        runBackup()
        {
            this.backupButton.text = 'Running Backup...';
            this.backupButton.disable = true;
            axios.get(this.route('admin.runBackup'))
                .then(res => {
                    this.getBackups();
                    this.backupButton.disable = false;
                    this.backupButton.text = 'Run Backup';
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
        }
    }
}
</script>

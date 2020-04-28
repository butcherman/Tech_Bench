<template>
    <div class="card">
        <div class="card-header">
            Customer Files:
            <b-button variant="primary" pill size="sm" class="float-right" @click="newFileForm">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add File
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Equipment...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Files</h4>
            </div>
            <vue-good-table v-else
                ref="customer-files-table"
                styleClass="vgt-table bordered w-100"
                :columns="table.columns"
                :rows="table.rows"
            >
                <template slot="emptystate">
                    <h4 class="text-center">No Files</h4>
                </template>
                <template slot="table-row" slot-scope="data">
                    <span v-if="data.column.field == 'name'">
                        <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.name}}</a>
                    </span>
                    <span v-else-if="data.column.field == 'actions'">
                        <i class="fas fa-pencil-alt pointer" title="Edit" v-b-tooltip @click="editFileForm(data.row)"></i>
                        <i class="fas fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteFile(data.row)"></i>
                    </span>
                </template>
            </vue-good-table>
        </div>
        <file-form ref="customer-file-form" :cust_id="cust_id" @completed="getFiles"></file-form>
    </div>
</template>

<script>
export default {
    props: {
        cust_id: {
        type:     Number,
        required: true,
        },
        linked: {
            type:     Boolean,
            required: false,
            default:  false,
        }
    },
    data() {
        return {
            error: false,
            loading: true,
            table: {
                columns: [
                    {
                        label: 'File Name',
                        field: 'name',
                        sortable: true,
                    },
                    {
                        label: 'File Type',
                        field: 'customer_file_types.description',
                        sortable: true,
                    },
                    {
                        label: 'Uploaded By',
                        field: 'user.full_name',
                        sortable: true,
                    },
                    {
                        label: 'Uploaded Date',
                        field: 'created_at',
                        sortable: true,
                    },
                    {
                        label: 'Actions',
                        field: 'actions',
                        sortable: false,
                    }
                ],
                rows: [],
            }
        }
    },
    mounted() {
        this.getFiles();
    },
    methods: {
        newFileForm()
        {
            this.$refs['customer-file-form'].initNewFile();
        },
        editFileForm(file)
        {
            this.$refs['customer-file-form'].initEditFile(file);
        },
        deleteFile(data)
        {
            this.$bvModal.msgBoxConfirm('Please confirm you want to delete the file.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        this.loading = true;
                        axios.delete(this.route('customer.files.destroy', data.cust_file_id))
                            .then(res => {
                                this.getFiles();
                            }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                });
        },
        getFiles()
        {
            axios.get(this.route('customer.files.show', this.cust_id))
                .then(res => {
                    this.loading = false;
                    this.table.rows = res.data;
                }).catch(error => this.error = true);
        },
    }
}
</script>

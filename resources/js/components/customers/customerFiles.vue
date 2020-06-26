<template>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    Customer Files:
                    <new-customer-file class="float-md-right mt-2 mt-md-0" :cust_id="cust_id" :linked_site="linked_site" @file-uploaded="getFiles"></new-customer-file>
                </div>
                <div class="card-body">
                    <div v-if="loading">
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Loading...</h4>
                    </div>
                    <div v-else-if="error">
                        <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Notes...</h5>
                    </div>
                    <vue-good-table
                        v-else
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
                                <a :href="route('download', [data.row.file_id, data.row.files.file_name])">{{data.row.name}}</a>
                            </span>
                            <span v-else-if="data.column.field == 'actions'">
                                <edit-customer-file
                                    :cust_id="cust_id"
                                    :linked_site="linked_site"
                                    :file_data="data.row"
                                    @file-updated="getFiles"
                                ></edit-customer-file>
                                <i class="fas fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteFile(data.row.cust_file_id)"></i>
                            </span>
                        </template>
                    </vue-good-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                loading: false,
                error:   false,
                table: {
                    columns:
                    [
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
        created() {
            //
        },
        mounted() {
            this.getFiles();
            this.eventHub.$on('parent-linked', data => {
                 this.getFiles();
             });
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            getFiles()
            {
                this.loading = true;
                axios.get(this.route('customer.files.show', this.cust_id))
                    .then(res => {
                        this.table.rows = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteFile(id)
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete this file.', {
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
                        axios.delete(this.route('customer.files.destroy', id))
                            .then(res => {
                                this.getFiles();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                });
            }
        }
    }
</script>

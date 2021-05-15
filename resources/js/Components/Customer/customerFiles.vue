<template>
    <div>
        <div class="card-title">
            Files:
            <new-file-modal v-if="permissions.create" :cust_id="cust_id" @upload-completed="getFiles"></new-file-modal>
        </div>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader text="Loading Files..."></atom-loader>
            </template>
            <div v-if="files.length > 0">
                <b-table
                    striped
                    responsive
                    :items="files"
                    :fields="fields"
                >
                    <template #cell(name)="row">
                        <i v-if="row.item.shared" class="fas fa-share" title="File Shared Across Sites" v-b-tooltip.hover></i>
                        <a :href="route('download', [row.item.file_upload.file_id, row.item.file_upload.file_name])" title="Click to Download" v-b-tooltip.hover>{{row.item.name}}</a>
                    </template>
                    <template #cell(actions)="data">
                        <!-- <edit-file-modal v-if="permissions.update" :cust_id="cust_id" :contact_data="data.item" @completed="getFiles"></edit-file-modal> -->

                        <b-button v-if="permissions.delete" pill size="sm" variant="light" title="Delete File" v-b-tooltip.hover @click="deleteFile(data.item.cust_file_id)">
                            <i class="far fa-trash-alt"></i>
                        </b-button>
                    </template>
                </b-table>
            </div>
            <div v-else>
                <h5 class="text-center">No Files Have Been Uploaded</h5>
            </div>
        </b-overlay>
    </div>
</template>

<script>
    import newFileModal from './Files/newFileModal.vue'

    export default {
        components: { newFileModal },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            customer_files: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                loading:  false,
                files:    this.customer_files,
                fields:   [
                    {
                        key:     'selected',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'name',
                        label:   'Name',
                        sortable: true,
                    },
                    {
                        key:     'file_type',
                        label:   'Type',
                        sortable: true,
                    },
                    {
                        key:     'uploaded_by',
                        label:   'Uploaded By',
                        sortable: true,
                    },
                    {
                        key:     'updated_at',
                        label:   'Date',
                        sortable: true,
                    },
                    {
                        key:      'actions',
                        label:    '',
                        sortable: false,
                    }
                ]
            }
        },
        methods: {
            getFiles()
            {
                this.loading = true;
                axios.get(this.route('customers.files.show', this.cust_id))
                    .then(res => {
                        this.files   = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },

            deleteFile(file)
            {
                this.$bvModal.msgBoxConfirm('Please Verify You Want Delete This File',
                    {
                        title:          'Are you sure?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            this.loading = true;
                            axios.delete(this.route('customers.files.destroy', file))
                                .catch(error => this.eventHub.$emit('axiosError', error));
                            this.getFiles();
                        }
                    });
            }
        },
    }
</script>

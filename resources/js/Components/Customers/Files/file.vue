<template>
    <div>
        <div v-if="files.length == 0">
            <h4 class="text-center">No Files</h4>
        </div>
        <div v-else>
            <b-table striped :items="files" :fields="fields" empty-text="No Files" :busy="loading" responsive show-empty>
                <template #table-busy>
                    <atom-loader />
                </template>
                <template #cell(name)="row">
                    <i v-if="row.item.shared" class="fas fa-share" title="File Shared Across Sites" v-b-tooltip.hover></i>
                    <a :href="route('download', [row.item.file_upload.file_id, row.item.file_upload.file_name])" title="Click to Download" v-b-tooltip.hover>{{row.item.name}}</a>
                </template>
                <template #cell(actions)="data">
                    <edit-file :cust_id="cust_id" :file="data.item" :allow_share="allow_share"></edit-file>
                    <b-button v-if="permissions.delete" pill size="sm" variant="light" title="Delete File" v-b-tooltip.hover @click="deleteFile(data.item.cust_file_id)">
                        <i class="far fa-trash-alt text-danger"></i>
                    </b-button>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
    import EditFile from './editFile.vue';

    export default {
        components: { EditFile },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            files: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
            }
        },
        data() {
            return {
                loading: false,
                fields: [
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
            deleteFile(fileId)
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
                            this.$inertia.delete(route('customers.files.destroy', fileId), {
                                onFinish: () => {
                                    this.loading = false;
                                }
                            });
                        }
                    });
            },
        },
    }
</script>

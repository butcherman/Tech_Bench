<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title clearfix">
                Files
                <new-file v-if="customerStore.allowPermission('files', 'create')" />
            </div>
            <div>
                <b-table
                    :items="customerStore.files"
                    :busy="loading"
                    :fields="fields"
                    empty-text="No Files"
                    striped
                    show-empty
                    responsive
                >
                    <template #table-busy>
                        <atom-loader />
                    </template>
                    <template #cell(name)="row">
                        <i
                            v-if="row.item.shared"
                            class="fas fa-share"
                            title="File Shared Across Sites"
                            v-b-tooltip.hover
                        />
                        <a
                            :href="route('download', [
                                row.item.file_upload.file_id,
                                row.item.file_upload.file_name
                            ])"
                            title="Click to Download"
                            v-b-tooltip.hover
                        >
                            {{row.item.name}}
                        </a>
                    </template>
                    <template #cell(actions)="data">
                        <edit-file :file="data.item" />
                        <delete-file :fileId="data.item.cust_file_id" />
                    </template>
                </b-table>
            </div>
        </div>
    </div>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        props: {
            //
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
                ],
            }
        },
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
            ...mapStores(useCustomerStore),
        },
        watch: {
            //
        },
        methods: {
            //
        },
    }
</script>

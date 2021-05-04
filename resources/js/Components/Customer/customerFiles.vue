<template>
    <div>
        <div class="card-title">
            Files:
            <new-file-modal :cust_id="cust_id" @upload-completed="getFiles"></new-file-modal>
        </div>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader text="Loading Files..."></atom-loader>
            </template>
            <div v-if="files.length > 0">
                <b-table
                    striped
                    selectable
                    responsive
                    :items="files"
                    :fields="fields"
                    select-mode="multi"
                    @row-selected="onRowSelect"
                >
                    <template #cell(selected)="{ rowSelected }">
                        <template v-if="rowSelected">
                            <span>&check;</span>
                        </template>
                    </template>
                    <template #cell(name)="row">
                        <a href="#" title="Click to Download" v-b-tooltip.hover>{{row.item.name}}</a>
                    </template>
                </b-table>
                <div class="text-center mt-2">
                    <b-button variant="danger">Delete Selected</b-button>
                </div>
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
            }
        },
        data() {
            return {
                loading: false,
                selected: [],
                files:   this.customer_files,
                fields: [
                    {
                        key:     'selected',
                        lable:   'Selected',
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
                ]
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
        },
        watch: {
            //
        },
        methods: {
            //
            getFiles()
            {
                console.log('get files');
            },
            onRowSelect(items)
            {
                this.selected = items;
            }
        },
    }
</script>

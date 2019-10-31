<template>
    <div>
        <img v-if="!loadDone" src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        <div v-else-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else class="table">
            <vue-good-table
                mode="remote"
                ref="files-table"
                styleClass="vgt-table bordered w-100"
                :columns="table.columns"
                :rows="table.rows"
                :select-options="{enabled:true, selectOnCheckboxOnly: true}"
                :sort-options="{enabled:true}"
                isLoading.sync="loadDone"
            >
                <div slot="emptystate">
                    <h4 class="text-center">No Files</h4>
                </div>
                <div slot="selected-row-actions">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <button id="delete-selected" class="btn btn-warning btn-block">Delete Selected Files</button>
                            <b-popover :target="'delete-selected'" triggers="focus" placement="bottom">
                                <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                                <div class="text-center">
                                    <button class="btn btn-danger" @click="deleteChecked">Yes</button>
                                    <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                                </div>
                            </b-popover>
                        </div>
                        <div class="col-md-3">
                            <button id="download-selected" class="btn btn-primary btn-block" @click="downloadChecked">Download Selected Files</button>
                        </div>
                    </div>
                </div>
                <template slot="table-row" slot-scope="data">
                    <span v-if="data.column.field == 'name'">
                        <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.files.file_name}}</a>
                    </span>
                    <span v-else-if="data.column.field == 'user'">
                        <span v-if="data.row.added_by">{{data.row.added_by}}</span>
                        <span v-else>{{data.row.user.full_name}}</span>
                    </span>
                    <span v-else-if="data.column.field == 'actions'">
                        <div class="d-flex flex-nowrap">
                            <button v-if="cust_id" class="btn btn-rounded px-0 text-muted mr-2" title="Link File To Customer Files" v-b-tooltip.hover>
                                <span class="ti-export"></span>
                            </button>
                            <button :id="'confirm-delete'+data.row.link_id" class="btn btn-rounded px-0 text-muted" title="Delete File" v-b-tooltip.hover>
                                <span class="ti-trash"></span>
                            </button>
                            <b-popover :target="'confirm-delete'+data.row.link_id" triggers="focus" placement="left">
                                <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                                <div class="text-center">
                                    <button class="btn btn-danger" @click="deleteFile(data.row.link_file_id)">Yes</button>
                                    <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                                </div>
                            </b-popover>
                        </div>
                    </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
            'cust_id',
            'file_types'
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                loadDone: false,
                error: false,
                table: {
                    columns: [
                        {
                            label: 'File Name',
                            field: 'name',
                            filterable: true,
                        },
                        {
                            label: 'Date Added',
                            field: 'created_at',
                            filterable: true,
                        },
                        {
                            label: 'Added By',
                            field: 'user',
                            filterable: true,
                        },
                        // {
                        //     label: 'File Notes',
                        //     field: 'note',
                        //     filterable: false,
                        // },
                        {
                            label: 'Actions',
                            field: 'actions',
                            filterable: false,
                        }
                    ],
                    rows: []
                }
            }
        },
        created() {
            this.getFiles();
            console.log(this.cust_id);
            console.log(this.file_types);
        },
        methods: {
            getFiles()
            {
                console.log(this.route('links.files.show'));
                axios.get(this.route('links.files.show', this.link_id))
                    .then(res => {
                        this.loadDone = true;
                        console.log(res);
                        this.table.rows = res.data;
                    }).catch(error => { this.error = true; });
            },
            downloadChecked()
            {
                console.log('download selected files');
            },
            deleteChecked()
            {
                console.log('delete selected files');
            },
            deleteFile()
            {
                console.log('delete file');
            }
        }
    }
</script>

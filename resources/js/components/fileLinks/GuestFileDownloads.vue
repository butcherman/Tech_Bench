<template>
    <div class="table">
        <vue-good-table v-if="loadDone"
            mode="remote"
            ref="files-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :select-options="{enabled:true, selectOnCheckboxOnly: true}"
            :sort-options="{enabled:true}"
            :isLoading.sync="isLoading"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Files Available</h4>
            </div>
            <div slot="selected-row-actions">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <button id="download-selected" class="btn btn-primary btn-block" @click="downloadChecked">Download Selected Files</button>
                    </div>
                </div>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.files.file_name}}</a>
                </span>
            </template>
        </vue-good-table>
        <div v-else-if="error">
            <h5 class="text-center text-danger">Problem Loading Files...</h5>
        </div>
        <div v-else>
            <p class="text-center">Gathering Files</p>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
        ],
        data() {
            return {
                loadDone: false,
                isLoading: true,
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
                    }],
                    rows: [],
                }
            }
        },
        created()
        {
            this.getFiles();
        },
        methods:
        {
            getFiles()
            {
                axios.get(this.route('file-links.getFiles', this.link_id))
                    .then(res => {
                        this.loadDone = true;
                        this.table.rows = res.data;
                        // this.loading = false;
                    }).catch(error => {this.error = true});
            },
            downloadChecked()
            {
                //  place all of the files in an array to be sent for zipping
                var fileList = [];
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    fileList.push(file.files.file_id);
                });

                //  prepare the zip file for download
                axios.put(this.route('archiveFiles'), {'fileList': fileList})
                    .then(res => {
                        window.location.href = this.route('downloadArchive', res.data.archive);
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
        }
    }
</script>

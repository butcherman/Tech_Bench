<template>
    <div class="table">
        <div v-if="error">
            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load files...</h5>
        </div>
        <div v-else-if="!loadDone">
            <p class="text-center">Gathering Files</p>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
        </div>
        <vue-good-table v-else
            ref="files-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :select-options="{enabled:true, selectOnCheckboxOnly: true}"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Files Available</h4>
            </div>
            <div slot="selected-row-actions">
                <button @click="downloadChecked" class="btn btn-warning float-right mb-2">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="downloadButton.disable"></span>
                    {{downloadButton.text}}
                </button>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.files.file_name}}</a>
                </span>
            </template>
        </vue-good-table>
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
                error: false,
                downloadButton: {
                    text: 'Download Selected',
                    disable: false,
                },
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
                    }).catch(error => {this.error = true});
            },
            downloadChecked()
            {
                this.downloadButton.text = 'Processing..';
                this.downloadButton.disable = true;

                //  place all of the files in an array to be sent for zipping
                var fileList = [];
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    fileList.push({file_id: file.file_id, file_name: file.files.file_name});
                });

                 //  prepare the zip file for download
                axios.post(this.route('dlArchive'), {'fileList': fileList})
                    .then(res => {
                        window.location.href        = this.route('downloadArchive', res.data.archive);
                        this.downloadButton.text    = 'Download Selected';
                        this.downloadButton.disable = false;
                    }).catch(error => this.$bvModal.msgBoxOk('Download Checked operation failed.  Please try again later.'));
            },
        }
    }
</script>

<template>
    <div class="table">
        <vue-good-table v-if="loadDone"
            mode="remote"
            ref="file-links-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :select-options="{enabled:true, selectOnCheckboxOnly: true}"
            :sort-options="{enabled:true}"
            :isLoading.sync="isLoading"
            :row-style-class="linkRowClass"
        >
            <div slot="emptystate">
                <h4 class="text-center">No File Links Available</h4>
            </div>
            <div slot="selected-row-actions">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <button id="delete-selected" class="btn btn-warning btn-block">Delete Selected</button>
                        <b-popover :target="'delete-selected'" triggers="focus" placement="bottom">
                            <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                            <div class="text-center">
                                <button class="btn btn-danger" @click="deleteChecked">Yes</button>
                                <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                            </div>
                        </b-popover>
                    </div>
                </div>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="route('links.details', [data.row.link_id, dashify(data.row.link_name)])">{{data.row.link_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <div class="d-flex justify-content-between flex-nowrap">
                        <a v-if="!data.row.expired" :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+route('file-links.show', [data.row.link_hash])" title="Email Link" class="btn btn-rounded px-0 text-muted" v-b-tooltip.hover><span class="ti-email"></span></a>
                        <button v-if="!data.row.expired" @click="disableLink(data.row.link_id)" class="btn btn-rounded px-0 text-muted" title="Disable Link" v-b-tooltip.hover>
                            <span class="ti-unlink"></span>
                        </button>
                        <button :id="'confirm-delete'+data.row.link_id" class="btn btn-rounded px-0 text-muted" title="Delete Link" v-b-tooltip.hover>
                            <span class="ti-trash"></span>
                        </button>
                        <b-popover :target="'confirm-delete'+data.row.link_id" triggers="focus" placement="left">
                            <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                            <div class="text-center">
                                <button class="btn btn-danger" @click="deleteLink(data.row.link_id)">Yes</button>
                                <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                            </div>
                        </b-popover>
                    </div>
                </span>
            </template>
        </vue-good-table>
        <div v-else>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
    </div>
</template>

<script>
    export default {
        props: [

        ],
        data() {
            return {
                loadDone: false,
                isLoading: true,
                table: {
                    columns: [
                    {
                        label: 'Link Name',
                        field: 'name',
                        filterable: true,
                    },
                    {
                        label: '# of Files',
                        field: 'file_count',
                        filterable: true,
                    },
                    {
                        label: 'Expire Date',
                        field: 'exp_format',
                        filterable: true,
                    },
                    {
                        label: 'Actions',
                        field: 'actions'
                    }],
                    rows: []
                }
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            fetchLinks()
            {
                axios.get(this.route('links.user', [0]))
                    .then(res => {
                        this.table.rows = res.data;
                        this.loadDone = true;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            linkRowClass(row)
            {
                return row.expired ? 'table-danger': '';
            },
            disableLink(link)
            {
                axios.get(this.route('links.disable', [link]))
                    .then(res => {
                        if(res.data.success)
                        {
                            this.fetchLinks();
                        }
                        else
                        {
                            alert('We are having difficulties processing your request\nPlease try again later.');
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            deleteLink(link)
            {
                axios.delete(this.route('links.data.destroy', [link]))
                    .then(res => {
                        if(res.data.success)
                        {
                            this.$root.$emit('bv::hide::popover')
                            this.fetchLinks();
                        }
                        else
                        {
                            alert('We are having difficulties processing your request\nPlease try again later.');
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            deleteChecked()
            {
                var list = this;
                this.$refs['file-links-table'].selectedRows.forEach(function(link)
                {
                    list.deleteLink(link.link_id);
                });
            },
        }
    }
</script>

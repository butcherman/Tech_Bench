<template>
    <div>
        <vue-good-table
            ref="file-links-table"
            styleClass="vgt-table striped bordered"
            :columns="cols"
            :rows="links"
            :select-options="{enabled: true, selectOnCheckboxOnly: true}"
            :sort-options="{enabled: true}"
        >
            <div slot="emptystate">
                <h3 class="text-center">No File Links</h3>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="data.row.url">{{data.row.link_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <a :href="em_link_route.replace(':hash', data.row.link_hash)" title="Email Link" class="text-muted remove-link" v-b-tooltip.hover><i class="fa fa-envelope" aria-hidden="true"></i></a>
                    <click-confirm class="d-inline">
                        <span class="pointer" @click="deleteLink(data.row.link_id)" title="Delete Link" v-b-tooltip.hover><i class="fa fa-trash"></i></span>
                    </click-confirm>
                </span>
                <span v-else>
                    {{data.formattedRow[data.column.field]}}
                </span>
            </template>
            <div slot="selected-row-actions">
                <button class="btn btn-info" @click="deleteChecked">Delete Selected</button>
            </div>
        </vue-good-table>
    </div> 
</template>

<script>
    export default {
        props: [
            'get_links_route',
            'del_link_route',
            'em_link_route',
        ],
        data() {
            return {
                cols: [
                    {
                        label: 'Link Name',
                        field: 'name',
                    },
                    {
                        label: '# of Files',
                        field: 'file_link_files_count',
                    },
                    {
                        label: 'Expire Date',
                        field: 'expire',
                    },
                    {
                        label: 'Actions',
                        field: 'actions',
                    }
                ],
                links:     [],
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            //  List the links the user owns
            fetchLinks() 
            {
                axios.get(this.get_links_route)
                    .then(res => {
                        this.links       = res.data;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Delete a single link
            deleteLink(linkID)
            {
                axios.delete(this.del_link_route.replace(':linkID', linkID))
                    .then(res => {
                        this.fetchLinks();
                    });
            },
            deleteChecked(data)
            {
                var obj = this;
                this.$refs['file-links-table'].selectedRows.forEach(function(link)
                {
                    obj.deleteLink(link.link_id);
                });
            }
        }
    }
</script>

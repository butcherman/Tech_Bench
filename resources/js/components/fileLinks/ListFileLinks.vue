<template>
    <div class="table">
        <div v-if="error">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-4">
                    <img src="/img/err_img/sry_error.png" alt="Error" id="header-logo" />
                    <!-- TODO - Replace this with head-scratch image -->
                </div>
                <div class="col-md-5">
                    <h3 class="text-danger">Something Bad Happened:</h3>
                    <p>
                        Sorry about that.
                    </p>
                    <p>
                        Our minions are busy at work to determine what happened.
                    </p>
                </div>
            </div>
        </div>
        <vue-good-table
            v-else
            mode="remote"
            ref="file-links-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :sort-options="{enabled:true}"
            :select-options="{enabled:true, selectOnCheckboxOnly: true}"
            :isLoading.sync="isLoading"
            :row-style-class="linkRowClass"
        >
            <div slot="emptystate">
                <h4 v-if="loadDone" class="text-center">No File Links Available</h4>
                <atom-spinner v-else
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <div slot="selected-row-actions">
                <button @click="deleteChecked" class="btn btn-warning btn-block">Delete Selected</button>
            </div>
            <div slot="loadingContent">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="route('links.details', [data.row.link_id, dashify(data.row.link_name)])">{{data.row.link_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <a v-if="!data.row.expired"
                        :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+route('file-links.show', [data.row.link_hash])"
                        title="Email Link"
                        class="text-muted pl-2"
                        v-b-tooltip.hover>
                            <span class="fas fa-envelope"></span>
                    </a>
                    <i v-if="!data.row.expired"
                        class="fas fa-unlink pointer pl-2"
                        title="Disable Link"
                        v-b-tooltip.hover
                        @click="disableLink(data.row.link_id)">
                    </i>
                    <i
                        class="fas fa-trash-alt pl-2"
                        title="Delete Link"
                        v-b-tooltip.hover
                        @click="deleteLinkConfirm(data.row.link_id)">
                    </i>
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
    export default {
        props: [
            'user_id',
        ],
        data() {
            return {
                loadDone: false,
                isLoading: true,
                error: false,
                table: {
                    columns: [
                    {
                        label: 'Link Name',
                        field: 'name',
                        sortable: true,
                    },
                    {
                        label: '# of Files',
                        field: 'file_count',
                        sortable: false,
                    },
                    {
                        label: 'Expire Date',
                        field: 'exp_format',
                        sortable: true,
                    },
                    {
                        label: 'Actions',
                        field: 'actions',
                        sortable: false,
                    }],
                    rows: []
                }
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            //  Ajax call to pull links for the user
            fetchLinks()
            {
                this.isLoading = true;
                var user = this.user_id ? this.user_id : 0
                axios.get(this.route('links.user', user))
                    .then(res => {
                        this.table.rows = res.data;
                        this.isLoading = false;
                        this.loadDone = true;
                    }).catch(error => this.error = true);
            },
            //  Determine if the link is expired and should be highlighted
            linkRowClass(row)
            {
                return row.expired ? 'table-danger': '';
            },
            //  Set the expire date of the link to yesterday
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
                            this.$bvModal.msgBoxOk('Disable link operation failed.  Please try again later.');
                        }
                    }).catch(error => this.error = true);
            },
            //  Verify that the link should be disabled
            deleteLinkConfirm(link)
            {
                this.$bvModal.msgBoxConfirm('This cannot be undone.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                })
                .then(res => {
                    if(res)
                    {
                        this.$root.$emit('bv::hide::popover');
                        this.isLoading = true;
                        this.deleteLink(link);
                    }
                });
            },
            //  Delete the link and any files associated with it
            deleteLink(link)
            {
                axios.delete(this.route('links.data.destroy', [link]))
                    .then(res => {
                        if(res.data.success)
                        {
                            this.fetchLinks();
                        }
                        else
                        {
                            this.$bvModal.msgBoxOk('Delete link operation failed.  Please try again later.');
                        }
                    }).catch(error => this.error = true);
            },
            //  Delete a series of links
            deleteChecked()
            {
                var list = this;
                this.$bvModal.msgBoxConfirm('This cannot be undone.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                })
                .then(res => {
                    if(res)
                    {
                        this.$root.$emit('bv::hide::popover');
                        this.isLoading = true;
                        this.$refs['file-links-table'].selectedRows.forEach(function(link)
                        {
                            list.deleteLink(link.link_id);
                        });
                    }
                });
            }
        }
    }
</script>

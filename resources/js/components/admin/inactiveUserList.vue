<template>
    <b-overlay :show="showOverlay">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <vue-good-table
            ref="userListTable"
            :columns="columns"
            :rows="rows"
            :sort-options="{enabled:true}"
            styleClass="vgt-table striped bordered"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Deactivated Users</h4>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'actions'">
                    <i class="fas fa-unlock-alt pointer" title="Enable User" v-b-tooltip.hover @click="enableUser(data.row, data.index)"></i>
                </span>
            </template>
        </vue-good-table>
        <reset-user-password ref="reset-password-modal"></reset-user-password>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            user_list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                showOverlay: false,
                columns: [
                    {
                        label:   'User',
                        field:   'full_name',
                        sortable: true,
                        filterOptions: {
                            enabled: true,
                        }
                    },
                    {
                        label:   'Email Address',
                        field:   'email',
                        sortable: true,
                        filterOptions: {
                            enabled: true,
                        }
                    },
                    {
                        label:   'Deactivated Date',
                        field:   'deleted_at',
                        sortable: true,
                    },
                    {
                        label:   'Actions',
                        field:   'actions',
                        sortable: false,
                    }
                ],
                rows: this.user_list,
            }
        },
        methods: {
            enableUser(data, index)
            {
                this.showOverlay = true;
                axios.get(this.route('admin.user.activate', data.user_id))
                .then(res => {
                    this.showOverlay = false;
                    this.$bvModal.msgBoxOk(data.full_name+' has been reactivated.  Please go to the user page to update their password.')
                        .then(value => {
                            this.rows.splice(index, 1);
                        });
                }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        }
    }
</script>

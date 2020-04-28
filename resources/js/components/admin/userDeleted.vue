<template>
    <div>
        <h4 class="text-center">Deactivated Users</h4>
        <vue-good-table
            ref="userListTable"
            :columns="columns"
            :rows="rows"
            :sort-options="{enabled:true}"
            styleClass="vgt-table striped bordered"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Disabled Users</h4>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'full_name'">
                    <a :href="route(action_route, data.row.user_id)">{{data.row.full_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="fas fa-unlock-alt pointer" title="Enable User" v-b-tooltip:hover @click="enableUser(data.row, data.index)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
        </b-modal>
    </div>
</template>
<script>
export default {
    props: [
        'user_list',
        'action_route',
    ],
    data() {
        return {
            columns: [
                {
                    label: 'User',
                    field: 'full_name',
                    sortable: true,
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'Email Address',
                    field: 'email',
                    sortable: true,
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'User Deactivated Date',
                    field: 'deleted_at',
                    sortable: true,
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    sortable: false,
                }
            ],
            rows: this.user_list,
        }
    },
    methods: {
        enableUser(data, index)
        {
            this.$refs['loading-modal'].show();
            axios.get(this.route('admin.user.reactivate', data.user_id))
                .then(res => {
                    this.$refs['loading-modal'].hide();
                    this.$bvModal.msgBoxOk(data.full_name+' has been reactivated.  Please go to the user page to update their password.')
                        .then(value => {
                            this.rows.splice(index, 1);
                        });
                }).catch(error => this.$bvModal.msgBoxOk('Unable to reactivate user at this time.  Please try again later.'));
        },
    }
}
</script>

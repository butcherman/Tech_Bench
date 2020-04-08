<template>
    <div>
        <h4 class="text-center">Deactivated Customers</h4>
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
                <span v-if="data.column.field == 'actions'">
                    <i class="fas fa-unlock-alt pointer" title="Enable Customer" v-b-tooltip:hover @click="enableCustomer(data.row, data.index)"></i>
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
        'cust_list',
    ],
    data() {
        return {
            columns: [
                {
                    label: 'Customer Name',
                    field: 'name',
                    sortable: true,
                    filterOptions: {
                        enabled: true,
                    }
                },

                {
                    label: 'Customer Deactivated Date',
                    field: 'deleted_at',
                    sortable: true,
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    sortable: false,
                }
            ],
            rows: this.cust_list,

            button: {
                disabled: false,
                text: 'Reset Password',
            }
        }
    },
    created() {
        // this.getUserList();
        console.log(this.cust_list);
    },
    methods: {
        enableCustomer(data, index)
        {
            this.$refs['loading-modal'].show();
            axios.get(this.route('admin.enableCustomer', data.cust_id))
                .then(res => {
                    this.$refs['loading-modal'].hide();
                    this.$bvModal.msgBoxOk(data.name+' has been reactivated')
                        .then(value => {
                            this.rows.splice(index, 1);
                        });
                }).catch(error => this.$bvModal.msgBoxOk('Unable to enable customer at this time.  Please try again later'));

        },
    }
}
</script>

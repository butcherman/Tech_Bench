<template>
    <div>
        <h4 class="text-center">Deactivated Users</h4>
        <vue-good-table
            :columns="columns"
            :rows="user_list"
            styleClass="vgt-table striped bordered"
        >
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'user'">
                    <a :href="route(action_route, data.row.user_id)">{{data.row.full_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="ti-unlock pointer" title="Enable User" v-b-tooltip:hover @click="enableUser(data.row, data.index)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
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
                    field: 'user',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'Email Address',
                    field: 'email',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'User Deactivated Date',
                    field: 'deleted_at',
                },
                {
                    label: 'Actions',
                    field: 'actions',
                }
            ],
            form: {
                password: '',
                password_confirmation: '',
                user_id: '',
                force_change: true,
            },
            validated: false,
            confirmedErr: null,
            modalTitle: 'Reset Password',
            generated: '',
            button: {
                disabled: false,
                text: 'Reset Password',
            }
        }
    },
    created() {
        // this.getUserList();
        console.log(this.user_list);
    },
    methods: {
        enableUser(data, index)
        {
            console.log(data);

            this.$refs['loading-modal'].show();
            axios.get(this.route('admin.user.reactivate', data.user_id))
                .then(res => {
                    console.log(res);
                    this.$refs['loading-modal'].hide();
                    this.$bvModal.msgBoxOk(data.full_name+' has been reactivated.  Please go to the user page to update their password.')
                        .then(value => {
                            location.reload();
                        });
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));

        },
    }
}
</script>

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
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'full_name'">
                    <a :href="route('admin.user.edit', data.row.user_id)" class="text-dark" title="Click to Edit User Details" v-b-tooltip.over>{{data.row.full_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'last_user_login'">
                    <span v-if="data.row.last_user_login != null">
                        <!-- <a :href="route('admin.user.login_history', [data.row.user_id, dashify(data.row.full_name)])" class="text-dark" title="Click for Login History" v-b-tooltip.hover> -->
                            {{data.row.last_user_login.created_at}}
                        <!-- </a> -->
                    </span>
                    <span v-else class="text-danger">
                        Never
                    </span>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="fas fa-key pointer" title="Reset Password" v-b-tooltip.hover @click="openResetForm(data.row)"></i>
                    <i class="fas fa-user-slash pointer" title="Disable User" v-b-tooltip.hover @click="disableUser(data.row, data.index)"></i>
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
                        label:   'Last Login Date',
                        field:   'last_user_login',
                        sortable: true,
                    },
                    {
                        label:   'Actions',
                        field:   'actions',
                        sortable: false,
                    }
                ],
                rows: this.user_list,
                modalTitle: 'Reset Password',

            }
        },
        methods: {
            openResetForm(row)
            {
                this.$refs['reset-password-modal'].openForm(row.full_name, row.user_id);
            },
            disableUser(data, index)
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+data.full_name, {
                    title:          'Please Confirm',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                })
                .then(value => {
                    if(value)
                    {
                        this.showOverlay = true;
                        axios.delete(this.route('admin.user.destroy', data.user_id))
                            .then(res => {
                                this.showOverlay = false;
                                this.$bvModal.msgBoxOk('User '+data.full_name+' has been deactivated')
                                    .then(value => {
                                        this.rows.splice(index, 1);
                                    });
                            }).catch(error => {
                                if(error.response.status === 403)
                                {
                                    this.$bvModal.msgBoxOk(error.response.data.message);
                                }
                                else
                                {
                                    this.eventHub.$emit('axiosError', error);
                                }
                                this.showOverlay = false;
                            });
                    }
                })
                .catch(error => {
                    this.eventHub.$emit('axiosError', error);
                });
            }
        }
    }
</script>

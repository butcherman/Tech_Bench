<template>
    <div>
        <h4 class="text-center">Select User to Modify</h4>
        <vue-good-table
            ref="userListTable"
            :columns="columns"
            :rows="rows"
            :sort-options="{enabled:true}"
            styleClass="vgt-table striped bordered"
        >
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'full_name'">
                    <a :href="route(action_route, data.row.user_id)">{{data.row.full_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="fas fa-key pointer" title="Reset Password" v-b-tooltip.hover v-b-modal.password-form-modal @click="modalTitle = 'Reset password for '+data.row.full_name; form.user_id = data.row.user_id"></i>
                    <i class="fas fa-user-slash pointer" title="Disable User" v-b-tooltip.hover @click="disableUser(data.row, data.index)"></i>
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
        <b-modal :title="modalTitle" id="password-form-modal" ref="passwordFormModal" hide-footer>
            <b-form @submit="changePassword" novalidate :validated="validated" ref="passwordForm">
                <b-form-group label="New Password" label-for="password">
                    <b-form-input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        placeholder="Enter User's New Password"
                    ></b-form-input>
                    <b-form-invalid-feedback>This field is Required</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Confirm Password" label-for="password_confirmed">
                    <b-form-input
                        id="password_confirmed"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        placeholder="Confirm User's New Password"
                        :state="confirmedErr"
                    ></b-form-input>
                    <b-form-invalid-feedback>The passwords do not match</b-form-invalid-feedback>
                </b-form-group>
                <div class="text-center" v-show="generated"><strong>Please Note New Password:</strong></div>
                <div class="text-center" v-show="generated">{{generated}}</div>
                    <b-form-checkbox switch v-model="form.force_change">Force User to Change Password on Next Login</b-form-checkbox>
                <b-button block variant="warning" class="mt-3" @click="generatePassword">Generate Random Password</b-button>
                <form-submit
                    :button_text="buttonText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
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
            buttonText: 'Reset Password',
            submitted: false,
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
                    label: 'Last Login Date',
                    field: 'last_user_login.created_at',
                    sortable: true,
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    sortable: false,
                }
            ],
            rows: this.user_list,
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
        }
    },
    methods: {
        disableUser(data, index)
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+data.full_name, {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('admin.user.destroy', data.user_id))
                        .then(res => {
                            this.$refs['loading-modal'].hide();
                            this.$bvModal.msgBoxOk(res.data.reason)
                                .then(value => {
                                    this.rows.splice(index, 1);
                                });
                        }).catch(error => this.$bvModal.msgBoxOk('Unable to disable user at this time.  Please try again later'));
                }
            })
            .catch(error => {
                this.$bvModal.msgBoxOk('Unable to disable user at this time.  Please try again later');
            });
        },
        changePassword(e)
        {
            this.confirmedErr = null;
            e.preventDefault();
            if(this.$refs.passwordForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else if(this.form.password !== this.form.password_confirmation)
            {
                this.confirmedErr = false;
                this.validated = false;
            }
            else
            {
                this.submitted = true;
                this.validated = true;
                this.confirmedErr = true;
                axios.post(this.route('admin.user.changePassword'), this.form)
                    .then(res => {
                        this.submitted = false;
                        this.$refs['passwordFormModal'].hide();
                        this.$bvModal.msgBoxOk(res.data.reason);
                    }).catch(error => this.$bvModal.msgBoxOk('Unable to reset password at this time.  Please try again later'));
            }
        },
        generatePassword()
        {
            var generator = require('generate-password');
            this.generated = generator.generate();
            this.form.password = this.generated;
            this.form.password_confirmation = this.generated;
        }
    }
}
</script>

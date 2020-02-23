<template>
    <div>
        <h4 class="text-center">Select User to Modify</h4>
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
                    <i class="fas fa-key pointer" title="Reset Password" v-b-tooltip:hover v-b-modal:password-form-modal @click="modalTitle = 'Reset password for '+data.row.full_name; form.user_id = data.row.user_id"></i>
                    <i class="fas fa-user-slash pointer" title="Disable User" v-b-tooltip:hover @click="disableUser(data.row, data.index)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
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
                    <div class="row justify-content-center mt-4">
                        <div class="col-6 col-md-2 order-2 order-md-1">
                            <div class="onoffswitch">
                                <input type="checkbox" v-model="form.force_change" class="onoffswitch-checkbox" id="forceChange" checked>
                                <label class="onoffswitch-label" for="forceChange">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10 align-self-center order-1 order-md-2">
                            <h5 class="text-center">Force User to Change Password on Next Login</h5>
                        </div>
                    </div>
                    <b-button block variant="warning" class="mt-3" @click="generatePassword">Generate Random Password</b-button>
                    <b-button type="submit" block variant="primary" class="mt-3" :disabled="button.disable">
                        <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                        {{button.text}}
                    </b-button>
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
                    label: 'Last Login Date',
                    field: 'last_user_login.created_at',
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
                // console.log(value);
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('admin.user.destroy', data.user_id))
                        .then(res => {
                            this.$refs['loading-modal'].hide();
                            this.$bvModal.msgBoxOk(res.data.reason)
                                .then(value => {
                                    location.reload();
                                });
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            })
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
                this.validated = true;
                this.confirmedErr = true;
                this.button.text = 'Processing...';
                this.button.disable = true;
                axios.post(this.route('admin.user.changePassword'), this.form)
                    .then(res => {
                        this.$refs['passwordFormModal'].hide();
                            this.$bvModal.msgBoxOk(res.data.reason)
                                .then(value => {
                                    location.reload();
                                });
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
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

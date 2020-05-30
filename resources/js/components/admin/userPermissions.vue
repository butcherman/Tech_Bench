<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <h4 class="text-center">Select A Role to Edit or Click to Add A New Row</h4>
                <b-form-select @change="selectRole" v-model="selectedRole">
                    <b-form-select-option value="null"></b-form-select-option>
                    <b-form-select-option v-for="role in roles_list" :key="role.role_id" :value="role">{{role.name}}</b-form-select-option>
                </b-form-select>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <b-button variant="warning" pill block @click="showBlankForm">Create New Role</b-button>
            </div>
        </div>
        <div class="row" v-show="showForm">
            <div class="col">
                <b-form @submit="validateForm" novalidate :validated="validated" ref="role-form">
                    <b-form-group label="Role Name" label-for="name">
                        <b-form-input
                            type="text"
                            placeholder="Enter A Name For This Role"
                            v-model="form.name"
                            :disabled="form.allow_edit == 0 ? true : false"
                            required
                        ></b-form-input>
                        <b-form-invalid-feedback>You must enter a name for this role</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Role Description" label-for="description">
                        <b-form-input
                            type="text"
                            placeholder="Enter A Brief Description For This Role"
                            v-model="form.description"
                            :disabled="form.allow_edit == 0 ? true : false"
                            required
                        ></b-form-input>
                        <b-form-invalid-feedback>You must enter a description for this role</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Role Permissions">
                        <div class="row">
                            <div class="col-4" v-for="opt in form.user_role_permissions" :key="opt.perm_type_id">
                                <b-form-checkbox
                                    v-model="opt.allow"
                                    value="1"
                                    unchecked-value="0"
                                    :disabled="form.allow_edit == 0 ? true : false"
                                    switch
                                >
                                    {{opt.description}}
                                </b-form-checkbox>
                            </div>
                        </div>
                    </b-form-group>
                    <form-submit :submitted="submitted" :button_text="btnText" :button_disable="!form.allow_edit"></form-submit>
                </b-form>
            </div>
        </div>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            roles_list: {
                type:     Array,
                required: true,
            },
            perms_list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                submitted:    false,
                showForm:     false,
                validated:    false,
                btnText:     'Create Role',
                selectedRole: null,
                form: {
                    role_id:               null,
                    name:                  null,
                    allow_edit:            false,
                    description:           null,
                    user_role_permissions: [],
                }
            }
        },
        methods: {
            selectRole(role)
            {
                this.form     = role;
                this.btnText  = role.allow_edit == 1 ? 'Update User Role' : 'Cannot Update This Role';
                this.showForm = true;
            },
            showBlankForm()
            {
                this.resetForm();
                this.showForm = true;
            },
            resetForm()
            {
                this.selectedRole     = null;
                this.btnText          = 'Create Role';
                this.form             = {};
                this.form.role_id     = null;
                this.form.name        = null;
                this.form.allow_edit  = true;
                this.form.description = null;
                this.form.user_role_permissions = this.perms_list;
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['role-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('admin.user.submit_role'), this.form)
                         .then(res => {
                             this.submitted = false;
                             this.showForm  = false;
                             this.resetForm();
                             this.$bvModal.msgBoxOk('Success!!!');
                         }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>

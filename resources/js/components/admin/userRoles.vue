 <template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <h4 class="text-center">Select A Role to Edit or Enter New Role Information Below</h4>
                <b-list-group>
                    <b-list-group-item button v-for="(item, key) in roles" :key="key" class="pointer text-center" @click="selectRole(item)">{{item.name}}</b-list-group-item>
                    <b-list-group-item button class="pointer text-center" @click="selectRole()">Create New Role</b-list-group-item>
                </b-list-group>
            </div>
        </div>
        <div class="row" v-show="showForm">
            <div class="col">
                <b-form @submit="submitRole" novalidate :validated="validated" ref="roleForm">
                    <b-form-group label="Role Name" label-for="name">
                        <b-form-input
                            type="text"
                            placeholder="Enter A Name For This Role"
                            v-model="form.name"
                            :disabled="form.edit"
                            required
                        ></b-form-input>
                        <b-form-invalid-feedback>You must enter a name for this role</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Role Description" label-for="description">
                        <b-form-input
                            type="text"
                            placeholder="Enter A Brief Description For This Role"
                            v-model="form.description"
                            :disabled="form.edit"
                            required
                        ></b-form-input>
                        <b-form-invalid-feedback>You must enter a description for this role</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Role Permissions">
                        <div class="row">
                            <div class="col-4" v-for="opt in form.permissions" :key="opt.perm_type_id">
                                <b-form-checkbox
                                    v-model="opt.allow"
                                    value="1"
                                    unchecked-value="0"
                                    :disabled="form.edit"
                                    switch
                                >
                                    {{opt.description}}
                                </b-form-checkbox>
                            </div>
                        </div>
                    </b-form-group>
                    <div class="row justify-content-center">
                        <div class="col-4 grid-margin">
                            <span class="spinner-border spinner-border-sm text-danger" v-show="button.submitted"></span>
                            <b-button type="submit" variant="primary" block :disabled="button.disable">{{button.text}}</b-button>
                        </div>
                    </div>
                </b-form>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: [
        'roles',
        'permissions',
    ],
    data() {
        return {
            showForm: false,
            options: [],
            validated: false,
            form: {
                role_id: null,
                name: '',
                edit: false,
                description: '',
                permissions: [],
            },
            button: {
                text: 'Create New Role',
                disabled: false,
                submitted: false,
            }
        }
    },
    created()
    {
        //
    },
    methods: {
        selectRole(data)
        {
            this.showForm = true;
            if(data)
            {
                this.form.role_id = data.role_id;
                this.form.name = data.name;
                this.form.description = data.description;
                this.form.edit = data.allow_edit == 0 ? true : false;
                this.form.permissions = data.user_role_permissions;
                this.button.text = data.allow_edit == 0 ? 'Unable to modify this role' : 'Update Role';
                this.button.disable = data.allow_edit == 0 ? true : false;
            }
            else
            {
                this.form.role_id = null;
                this.form.name = '';
                this.form.description = '';
                this.form.edit = false;
                this.form.permissions = this.permissions
                this.button.text = 'Create Role';
                this.button.disable = false;
            }
        },
        submitRole(e)
        {
            e.preventDefault();
            if(this.$refs.roleForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                axios.post(this.route('admin.roleSettings'), this.form)
                    .then(res => {
                        var msg = 'Success';
                        if(!res.data.success)
                        {
                            msg = res.data.reason
                        }
                        this.$bvModal.msgBoxOk(msg)
                            .then(value => {
                                location.reload();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        }
    }
}
</script>

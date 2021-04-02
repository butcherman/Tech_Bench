<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Create New User</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Enter New User Information</div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.username" rules="required" label="Username" name="username" :errors="errors"></text-input>
                                    <text-input v-model="form.first_name" rules="required" label="First Name" name="first_name" :errors="errors"></text-input>
                                    <text-input v-model="form.last_name" rules="required" label="Last Name" name="last_name" :errors="errors"></text-input>
                                    <text-input v-model="form.email" rules="required|email" label="Email Address" name="email" :errors="errors"></text-input>
                                    <b-form-select v-model="form.role_id" :options="roles" text-field="name" value-field="role_id"></b-form-select>
                                    <submit-button :button_text="button_text" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            roles: {
                type:     Array,
                required: true,
            },
            user_details: {
                type:     Object,
                required: false,
                default:  function()
                {
                    return {
                        username:   '',
                        first_name: '',
                        last_name:  '',
                        email:      '',
                        role_id:    4,
                    }
                }
            },
            errors: {
                type:     Object,
                required: false,
            }
        },
        data() {
            return {
                submitted: false,
                form:      this.$inertia.form(this.user_details),
            }
        },
        computed: {
            newUser()
            {
                return this.$options.propsData.user_details ? false : true;
            },
            button_text()
            {
                return this.newUser ? 'Create New User' : 'Update User';
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;

                if(this.newUser)
                {
                    this.form.post(route('admin.user.store'), {onFinish: () => {
                        this.form.reset();
                        this.submitted = false;
                    }});
                }
                else
                {
                    console.log('updating');
                    this.form.put(route('admin.user.update', this.user_details.user_id), {onFinish: () => {
                        this.submitted = false
                    }});
                }
            }
        }
    }
</script>

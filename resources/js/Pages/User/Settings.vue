<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">User Settings</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Account</div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submit.userData">
                                <template #overlay>
                                    <form-loader></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitUserData)" novalidate>
                                    <text-input v-model="userData.first_name" rules="required" label="First Name" name="first_name"></text-input>
                                    <text-input v-model="userData.last_name" rules="required" label="Last Name" name="last_name"></text-input>
                                    <text-input v-model="userData.email" rules="required|email" label="Email Address" name="email"></text-input>
                                    <submit-button button_text="Update Settings" :submitted="submit.userData" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submit.settings" class="h-100">
                                <template #overlay>
                                    <form-loader></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitSettings)" novalidate class="h-100 d-flex flex-column">
                                    <div class="card-title">Settings</div>
                                    <b-form-checkbox
                                        v-for="(item, key) in settings"
                                        :key="key"
                                        v-model="userSettings[key].value"
                                        switch
                                    >
                                        {{item.user_setting_type.name}}
                                    </b-form-checkbox>
                                    <submit-button class="mt-auto" button_text="Update Notifications" :submitted="submit.settings" />
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
            user: {
                type:     Object,
                required: true,
            },
            settings: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                userData: {
                    first_name: this.user.first_name,
                    last_name:  this.user.last_name,
                    email:      this.user.email,
                },
                userSettings: this.settings,
                submit: {
                    userData: false,
                    settings: false,
                },
            }
        },
        methods: {
            submitUserData()
            {
                this.submit.userData = true;
                this.eventHub.$emit('clear-alert');
                this.$inertia.put(route('settings.update', this.user.user_id), this.userData, {
                    onFinish: () => {
                    this.submit.userData = false
                        if(Object.keys(this.$page.props.errors).length > 0)
                        {
                            this.eventHub.$emit('show-alert', {
                                message: 'Update Settings Failed.  Check form for additional information.',
                                type:    'danger',
                            });
                        }
                    }
                });
            },
            submitSettings()
            {
                this.submit.settings = true;
                this.$inertia.post(route('settings.store'), {settingsData: this.userSettings, user_id: this.user.user_id}, {
                    onFinish: () => {
                        this.submit.settings = false
                    }
                });
            }
        },
        metaInfo: {
            title: 'User Settings',
        }
    }
</script>

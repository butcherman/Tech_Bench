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
                            <b-overlay :show="submit.settings">
                                <b-form @submit.prevent="handleSubmit(submitSettings)" novalidate>
                                    <text-input v-model="settings.first_name" rules="required" label="First Name" name="first_name"></text-input>
                                    <text-input v-model="settings.last_name" rules="required" label="Last Name" name="last_name"></text-input>
                                    <text-input v-model="settings.email" rules="required|email" label="Email Address" name="email"></text-input>
                                    <submit-button button_text="Update Settings" :submitted="submit.settings" />
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
                            <b-overlay :show="submit.notifications" class="h-100">
                                <b-form @submit.prevent="handleSubmit(submitNotifications)" novalidate class="h-100 d-flex flex-column">
                                    <div class="card-title">Notification Settings</div>
                                    <b-form-checkbox
                                        v-model="notifications.em_tech_tip"
                                        value="1"
                                        unchecked-value="0"
                                        switch
                                    >
                                        Recieve Email On New Tech Tip
                                    </b-form-checkbox>
                                    <b-form-checkbox
                                        v-model="notifications.em_notification"
                                        value="1"
                                        unchecked-value="0"
                                        switch
                                    >
                                        Recieve Email On System Notification
                                    </b-form-checkbox>
                                    <b-form-checkbox
                                        v-model="notifications.em_file_link"
                                        value="1"
                                        unchecked-value="0"
                                        switch
                                    >
                                        Recieve Email When A New File Is Added To A File Link
                                    </b-form-checkbox>
                                    <b-form-checkbox
                                        v-model="notifications.auto_del_link"
                                        value="1"
                                        unchecked-value="0"
                                        switch
                                    >
                                        Automatically Delete File Links Expired More Than 30 Days
                                    </b-form-checkbox>
                                    <submit-button class="mt-auto" button_text="Update Notifications" :submitted="submit.notifications" />
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
            notify: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                settings: {
                    first_name: this.user.first_name,
                    last_name:  this.user.last_name,
                    email:      this.user.email,
                },
                notifications: {
                    em_tech_tip:     this.notify.em_tech_tip,
                    em_notification: this.notify.em_notification,
                    em_file_link:    this.notify.em_file_link,
                    auto_del_link:   this.notify.auto_del_link,
                },
                submit: {
                    settings:      false,
                    notifications: false,
                }
            }
        },
        methods: {
            submitSettings()
            {
                this.submit.settings = true;
                this.$inertia.put(route('settings.update', this.user.user_id), this.settings, {onFinish: () => {this.submit.settings = false}});
            },
            submitNotifications()
            {
                this.submit.notifications = true;
                this.$inertia.put(route('email-notifications.update', this.user.user_id), this.notifications, {onFinish: () => {this.submit.notifications = false}});
            }
        }
    }
</script>

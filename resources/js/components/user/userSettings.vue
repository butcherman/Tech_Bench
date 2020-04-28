<template>
    <div class="h-100">
        <b-form @submit="validateForm" novalidate :validated="validated" ref="userSettingsForm" class="h-100 d-flex flex-column">
            <fieldset :disabled="submitted" class="mb-2">
                <b-form-checkbox
                    v-model="form.em_tech_tip"
                    value="1"
                    unchecked-value="0"
                    switch
                >
                    Recieve Email On New Tech Tip
                </b-form-checkbox>
                <b-form-checkbox
                    v-model="form.em_notification"
                    value="1"
                    unchecked-value="0"
                    switch
                >
                    Recieve Email On System Notification
                </b-form-checkbox>
                <b-form-checkbox
                    v-model="form.em_file_link"
                    value="1"
                    unchecked-value="0"
                    switch
                >
                    Recieve Email When A New File Is Added To A File Link
                </b-form-checkbox>
                <b-form-checkbox
                    v-model="form.auto_del_link"
                    value="1"
                    unchecked-value="0"
                    switch
                >
                    Automatically Delete File Links Expired More Than 30 Days
                </b-form-checkbox>
            </fieldset>
            <form-submit
                class="mt-auto"
                button_text="Update Notification Settings"
                :submitted="submitted"
            ></form-submit>
            <b-button :href="route('changePassword')" variant="warning" class="mt-2">Change Password</b-button>
        </b-form>
    </div>
</template>

<script>
export default {
    props: [
        'user_settings',
    ],
    data() {
        return {
            validated: false,
            submitted: false,
            form: {
                em_tech_tip:     this.user_settings.em_tech_tip,
                em_notification: this.user_settings.em_notification,
                em_file_link:    this.user_settings.em_file_link,
                auto_del_link:   this.user_settings.auto_del_link,
            },

        }
    },
    methods: {
        validateForm(e)
        {
            e.preventDefault();
            this.submitted = true;
            axios.put(this.route('account'), this.form)
                .then(res => {
                    if(res.data.success)
                    {
                        this.submitted = false;
                        this.validated = false;
                        this.$bvModal.msgBoxOk('Notification Settings Updated');
                    }
                    else
                    {
                        this.$bvModal.msgBoxOk('Unable to update notification settings.  Please try again later.');
                    }
                }).catch(error => this.$bvModal.msgBoxOk('Unable to update notification settings.  Please try again later.'));
        }
    }
}
</script>

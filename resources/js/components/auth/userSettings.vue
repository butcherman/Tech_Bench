<template>
    <b-overlay :show="submitted" class="h-100">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="user-settings-form" class="h-100 d-flex flex-column">
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
            <form-submit
                class="mt-auto"
                button_text="Update Notification Settings"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            user_settings: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    em_tech_tip:     this.user_settings.em_tech_tip,
                    em_notification: this.user_settings.em_notification,
                    em_file_link:    this.user_settings.em_file_link,
                    auto_del_link:   this.user_settings.auto_del_link,
                }
            }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['user-settings-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.put(this.route('update_settings'), this.form)
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
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>

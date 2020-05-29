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
        <b-form @submit="validateForm" novalidate :validated="validated" ref="password-policy-form">
            <b-form-group label="Password Expires in Days:" label-for="expire">
                <b-form-input
                    id="expire"
                    type="number"
                    v-model="form.expire"
                    required
                    placeholder="Enter 0 For Never Expires"
                    autocomplete="false"
                ></b-form-input>
                <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
            </b-form-group>
            <form-submit
                button_text="Update Password Policy"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            expire: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    expire: this.expire,
                }
            }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['password-policy-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('admin.user.submit_password_policy'), this.form)
                         .then(res => {
                             this.$bvModal.msgBoxOk('Password Policy Updated');
                             this.submitted = false;
                         }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>

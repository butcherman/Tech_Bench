<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-note-modal>
        <i class="fas fa-plus"></i>
        New
        <b-modal
            id="new-note-modal"
            ref="new-note-modal"
            title="Add New Note"
            hide-footer
            @hidden="resetForm"
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input label="Subject" v-model="form.subject"></text-input>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <b-form-checkbox v-model="form.urgent" switch>Mark Note As Important</b-form-checkbox>
                                <b-form-checkbox v-model="form.shared" switch>Share Note Across All Sites</b-form-checkbox>
                            </div>
                        </div>
                        <b-form-textarea v-model="form.details" placeholder="Enter Note" rows="5"></b-form-textarea>
                        <submit-button class="mt-2" button_text="Add Note" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id: this.cust_id,
                    subject: '',
                    details: '',
                    shared:  false,
                    urgent:  false,
                }
            }
        },
        methods: {
            resetForm()
            {
                this.form = {
                    cust_id: this.cust_id,
                    subject: '',
                    details: '',
                    shared:  false,
                    urgent:  false,
                };
            },
            submitForm()
            {
                this.loading   = true;
                this.submitted = true;
                axios.post(this.route('customers.notes.store'), this.form)
                    .then(() => {
                        this.$refs['new-note-modal'].hide();
                        this.$emit('completed');
                        this.loading   = false;
                        this.submitted = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
        },
    }
</script>

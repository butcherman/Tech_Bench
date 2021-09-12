<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-note-modal>
        <i class="fas fa-plus"></i>
        New
        <b-modal
            id="new-note-modal"
            ref="new-note-modal"
            title="Add New Note"
            size="lg"
            hide-footer
            @hidden="resetForm"
            no-enforce-focus
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input label="Title" v-model="form.subject" rules="required"></text-input>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <b-form-checkbox v-model="form.urgent" switch>Mark Note As Important</b-form-checkbox>
                                <b-form-checkbox v-if="allow_share" v-model="form.shared" switch>Share Note Across All Sites</b-form-checkbox>
                            </div>
                        </div>
                        <text-editor v-model="form.details" placeholder="Enter Note" label="Note Details" rules="required"></text-editor>
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
            },
            allow_share: {
                type:    Boolean,
                default: false,
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
            submitForm()
            {
                this.loading   = true;
                this.submitted = true;
                this.$inertia.post(this.route('customers.notes.store'), this.form, {
                    onFinish: () => {
                        this.loading = false;
                        this.submitted = false;
                        this.$refs['new-note-modal'].hide();
                    }
                });
            },
            resetForm()
            {
                this.form = {
                    cust_id: this.cust_id,
                    subject: '',
                    details: '',
                    shared:  false,
                    urgent:  false,
                }
            }
        },
    }
</script>

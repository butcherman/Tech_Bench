<template>
    <b-button variant="warning" @click="$refs['edit-note-modal'].show()">
        Edit
        <b-modal
            ref="edit-note-modal"
            title="Edit Note"
            hide-footer
            size="lg"
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
                        <text-editor v-model="form.details" placeholder="Enter Note" label="Note Details" rules="required"></text-editor>
                        <submit-button class="mt-2" button_text="Update Note" :submitted="submitted"></submit-button>
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
            note: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id: this.note.cust_id,
                    subject: this.note.subject,
                    details: this.note.details,
                    shared:  this.note.shared,
                    urgent:  this.note.urgent,
                }
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.loading = true;
                axios.put(this.route('customers.notes.update', this.note.note_id), this.form)
                    .then(() => {
                        this.$refs['edit-note-modal'].hide();
                        this.$emit('completed');
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
    }
</script>

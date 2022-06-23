<template>
    <b-button
        variant="warning"
        v-b-modal.edit-note-modal
    >
        <i class="fas fa-pencil-alt" />
        Edit
        <b-modal
            id="edit-note-modal"
            ref="edit-note-modal"
            title="Edit Note"
            size="lg"
            hide-footer
            no-enforce-focus
        >
            <b-overlay :show="submitted">
                <template #overlay>
                    <form-loader />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form
                        @submit.prevent="handleSubmit(submitForm)"
                        novalidate
                    >
                        <text-input label="Subject" v-model="form.subject" />
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <b-form-checkbox
                                    v-model="form.urgent"
                                    switch
                                >
                                    Mark Note As Important
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-if="customerStore.allowShare"
                                    v-model="form.shared"
                                    switch
                                >
                                    Share Note Across All Sites
                                </b-form-checkbox>
                            </div>
                        </div>
                        <text-editor
                            v-model="form.details"
                            placeholder="Enter Note"
                            label="Note Details"
                            rules="required"
                        />
                        <submit-button
                            class="mt-2"
                            button_text="Update Note"
                            :submitted="submitted"
                        />
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        props: {
            note: {
                type    : Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form     : this.$inertia.form({
                    cust_id: this.note.cust_id,
                    subject: this.note.subject,
                    details: this.note.details,
                    shared : this.note.shared,
                    urgent : this.note.urgent,
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('customers.notes.update', this.note.note_id), {
                    only     : ['notes', 'flash', 'errors'],
                    onSuccess: ()      => {
                        this.$refs['edit-note-modal'].hide();
                        this.$emit('completed');
                    },
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish : ()      => this.submitted = false,
                });
            }
        },
    }
</script>

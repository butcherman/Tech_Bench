<template>
    <b-button
        size="sm"
        variant="primary"
        class="float-right"
        pill
        v-b-modal.new-note-modal
    >
        <i class="fas fa-plus" />
        New
        <b-modal
            id="new-note-modal"
            ref="new-note-modal"
            title="Add New Note"
            size="lg"
            hide-footer
            no-enforce-focus
            @hidden="form.reset()"
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
                        <text-input
                            label="Title"
                            v-model="form.subject"
                            rules="required"
                        />
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
                            button_text="Add Note"
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
            //
        },
        data() {
            return {
                submitted: false,
                form     : this.$inertia.form({
                    cust_id: null,
                    subject: '',
                    details: '',
                    shared : false,
                    urgent : false,
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            submitForm()
            {
                this.submitted    = true;
                this.form.cust_id = this.customerStore.cust_id;

                this.form.post(route('customers.notes.store'), {
                    only     : ['notes', 'flash', 'errors'],
                    onSuccess: ()      => this.$refs['new-note-modal'].hide(),
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish : ()      => this.submitted = false,
                });
            }
        },
    }
</script>

<template>
    <b-button
        variant="warning"
        size="sm"
        pill
        @click="$refs['edit-file-modal'].show()"
    >
        <i class="fas fa-pencil-alt" />
        <b-modal
            ref="edit-file-modal"
            title="Edit File"
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
                        <text-input
                            v-model="form.name"
                            name="name"
                            label="Name"
                            rules="required"
                            placeholder="Enter a Descriptive Name"
                        />
                        <dropdown-input
                            v-model="form.type"
                            :options="file_types"
                            name="type"
                            rules="required"
                            label="File Type"
                            value-field="description"
                            text-field="description"
                            placeholder="What Type of File Is This?"
                        />
                        <b-form-checkbox
                            v-model="form.shared"
                            v-if="customerStore.allowShare"
                            class="text-center"
                            switch
                        >
                            Share File Across All Sites
                        </b-form-checkbox>
                        <submit-button
                            class="mt-2"
                            button_text="Update File"
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
            file: {
                type    : Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form     : this.$inertia.form({
                    cust_id: null,
                    name   : this.file.name,
                    type   : this.file.file_type,
                    shared : this.file.shared,
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            file_types() {
                return this.$page.props.file_types;
            }
        },
        methods: {
            submitForm()
            {
                this.form.cust_id = this.customerStore.cust_id;
                this.submitted = true;
                this.form.put(route('customers.files.update', this.file.cust_file_id), {
                    only     : ['files', 'flash', 'errors'],
                    onSuccess: ()      => this.$refs['edit-file-modal'].hide(),
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish : ()      => this.submitted = false,
                });
            }
        },
    }
</script>

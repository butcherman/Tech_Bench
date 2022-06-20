<template>
    <b-button
        size="sm"
        variant="primary"
        class="float-right"
        pill
        v-b-modal.new-contact-modal
    >
        <i class="fas fa-plus" />
        New
        <b-modal
            id="new-contact-modal"
            title="Add contact"
            ref="new-contact-modal"
            hide-footer
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
                            label="Contact Name"
                            name="name"
                            v-model="form.name"
                            rules="required"
                        />
                        <text-input
                            label="Title"
                            name="title"
                            v-model="form.title"
                        />
                        <text-input
                            label="Email Address"
                            name="email"
                            v-model="form.email"
                            rules="email"
                        />
                        <b-form-textarea
                            v-model="form.note"
                            placeholder="Notes about this contact..."
                            rows="3"
                        />
                        <b-form-checkbox
                            v-show="customerStore.allowShare"
                            v-model="form.shared"
                            class="text-center"
                            switch
                        >
                            Share Contact Across All Sites
                        </b-form-checkbox>
                        <b-form-group
                            label="Phone Numbers"
                            label-for="numbers"
                            class="mt-2"
                        >
                            <div
                                v-for="(data, key) in form.phones"
                                :key="key"
                                class="row mt-2"
                            >
                                <div class="col-sm-3 col-4 pr-1">
                                    <b-form-select
                                        v-model="data.type"
                                        :options="phone_types"
                                        value-field="description"
                                        text-field="description"
                                    />
                                </div>
                                <div class="col-sm-5 col-4 px-1">
                                    <vue-phone-number-input
                                        v-model="data.number"
                                        no-country-selector
                                    />
                                    <b-form-invalid-feedback>
                                        Please use a valid phone number format
                                    </b-form-invalid-feedback>
                                </div>
                                <div class="col-2 px-1">
                                    <b-form-input
                                        type="text"
                                        v-model="data.extension"
                                        placeholder="Ext"
                                    />
                                </div>
                                <div class="col-1">
                                    <b-button
                                        variant="danger"
                                        title="Remove Number"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        @click="removeRow(key)"
                                    >
                                        <i class="fas fa-phone-slash" />
                                    </b-button>
                                </div>
                            </div>
                        </b-form-group>
                        <b-button
                            size="sm"
                            variant="primary"
                            class="float-right mb-3"
                            pill
                            @click="addRow"
                        >
                            <i class="fas fa-plus d-none d-sm-inline" />
                            Add Row
                        </b-button>
                        <submit-button
                            button_text="Add Contact"
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
    import VuePhoneNumberInput  from 'vue-phone-number-input';

    export default {
        components: { VuePhoneNumberInput },
        data() {
            return {
                submitted: false,
                form     : this.$inertia.form({
                    cust_id: null,
                    name   : '',
                    title  : '',
                    email  : '',
                    note   : '',
                    shared : false,
                    phones : [
                        {
                            type:      'Mobile',
                            number:    '',
                            extension: '',
                        },
                    ],
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            phone_types()
            {
                return this.$page.props.phone_types;
            }
        },
        methods: {
            removeRow(key)
            {
                this.form.phones.splice(key, 1);
            },
            addRow()
            {
                this.form.phones.push({
                    type     : 'Mobile',
                    number   : '',
                    extension: '',
                });
            },
            submitForm()
            {
                this.submitted = true;

                this.form.cust_id = this.customerStore.cust_id;
                this.form.post(route('customers.contacts.store'), {
                    only     : ['contacts', 'flash', 'errors'],
                    onSuccess: ()      => {
                        this.$refs['new-contact-modal'].hide()
                        this.form.reset();
                    },
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish : ()      => this.submitted = false,
                });
            }
        },
    }
</script>

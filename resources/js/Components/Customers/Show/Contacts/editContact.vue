<template>
    <b-button
        variant="warning"
        size="sm"
        pill
        @click="$refs['edit-contact-modal'].show()"
    >
        <i class="fas fa-pencil-alt" />
        <b-modal
            ref="edit-contact-modal"
            title="Edit Contact"
            hide-footer
            @show="populateForm"
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
                                        v-model="data.phone_number_type.description"
                                        :options="phone_types"
                                        value-field="description"
                                        text-field="description"
                                    />
                                </div>
                                <div class="col-sm-5 col-4 px-1">
                                    <vue-phone-number-input
                                        v-model="data.phone_number"
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
                            button_text="Update Contact"
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
        props: {
            contIndex: {
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form     : {},
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
            submitForm()
            {
                this.submitted = true;
                let contactId = this.customerStore.contacts[this.contIndex].cont_id;

                this.form.put(route('customers.contacts.update', contactId), {
                    only     : ['contacts', 'flash', 'errors'],
                    onError  : (error) => this.eventHub.$emit('validationError', error),
                    onSuccess: ()      => this.$refs['edit-contact-modal'].hide(),
                    onFinish : ()      => this.submitted = false,
                });
            },
            populateForm()
            {
                this.form = this.$inertia.form({
                    cust_id: this.customerStore.cust_id,
                    name   : this.customerStore.contacts[this.contIndex].name,
                    title  : this.customerStore.contacts[this.contIndex].title,
                    email  : this.customerStore.contacts[this.contIndex].email,
                    note   : this.customerStore.contacts[this.contIndex].note,
                    shared : this.customerStore.contacts[this.contIndex].shared,
                    phones : [],
                });

                this.customerStore.contacts[this.contIndex].customer_contact_phone.forEach(cont => {
                    this.form.phones.push({
                        phone_number     : cont.phone_number,
                        extension        : cont.extension,
                        phone_number_type: {
                            description: cont.phone_number_type.description,
                        },
                    });
                });

                this.addRow();
            },
            removeRow(key)
            {
                this.form.phones.splice(key, 1);
            },
            addRow()
            {
                this.form.phones.push({
                   phone_number_type: {
                        description:      'Mobile',
                    },
                    phone_number:  '',
                    extension:     '',
                });
            },
        },
    }
</script>

<template>
    <b-button pill size="sm" variant="light" @click="$refs['edit-contact-modal'].show();" title="Edit" v-b-tooltip.hover>
        <i class="far fa-edit"></i>
        <b-modal
            ref="edit-contact-modal"
            title="Update Contact"
            hide-footer
        >
            <b-overlay :show="loading">
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input label="Contact Name" name="name" v-model="form.name" rules="required"></text-input>
                        <text-input label="Email Address" name="email" v-model="form.email" rules="email"></text-input>
                        <b-form-checkbox v-model="form.shared" class="text-center" switch>Share Contact Across All Sites</b-form-checkbox>
                        <b-form-group label="Phone Numbers" label-for="numbers" class="mt-2">
                            <div class="row mt-2" v-for="(data, key) in form.phones" :key="key">
                                <!-- {{data}} -->
                                <div class="col-sm-3 col-4 pr-1">
                                    <b-form-select v-model="data.phone_number_type.description" :options="phone_types" value-field="description" text-field="description"></b-form-select>
                                </div>
                                <div class="col-sm-5 col-4 px-1">
                                    <vue-phone-number-input v-model="data.phone_number" no-country-selector ></vue-phone-number-input>
                                    <b-form-invalid-feedback>Please use a valid phone number format</b-form-invalid-feedback>
                                </div>
                                <div class="col-2 px-1">
                                    <b-form-input type="text" v-model="data.extension" placeholder="Ext"></b-form-input>
                                </div>
                                <div class="col-1">
                                    <b-button variant="danger" title="Remove Number" size="sm" pill v-b-tooltip.hover @click="removeRow(key)"><i class="fas fa-phone-slash"></i></b-button>
                                </div>
                            </div>
                        </b-form-group>
                        <b-button size="sm" variant="primary" class="float-right mb-3" pill @click="addRow"><i class="fas fa-plus d-none d-sm-inline" aria-hidden="true"></i> Add Row</b-button>
                        <submit-button button_text="Update Contact" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import VuePhoneNumberInput from 'vue-phone-number-input';

    export default {
        components: { VuePhoneNumberInput },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            contact_data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id: this.cust_id,
                    name:    this.contact_data.name,
                    email:   this.contact_data.email,
                    shared:  this.contact_data.shared,
                    phones:  this.contact_data.customer_contact_phone,
                },
            }
        },
        computed: {
            errors()
            {
                return this.$page.props.errors;
            },
            phone_types()
            {
                return this.$page.props.phone_types;
            }
        },
        methods: {
            submitForm()
            {
                console.log(this.form);
                this.submitted = true;
                this.loading   = true;
                this.$inertia.put(route('customers.contacts.update', this.contact_data.cont_id), this.form, {onFinish: () => {
                    this.$refs['edit-contact-modal'].hide();
                    this.$emit('completed');
                }});
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
            removeRow(key)
            {
                this.form.phones.splice(key, 1);
            },
        },
    }
</script>

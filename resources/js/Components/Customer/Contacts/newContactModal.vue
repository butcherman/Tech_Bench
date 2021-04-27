<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-contact-modal>
        <i class="fas fa-plus"></i>
        New
        <b-modal
            id="new-contact-modal"
            ref="new-contact-modal"
            title="Add New Contact"
            hide-footer
        >
            <b-overlay :show="loading">
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input label="Contact Name" name="name" v-model="form.name" rules="required"></text-input>
                        <text-input label="Title" name="title" v-model="form.title"></text-input>
                        <text-input label="Email Address" name="email" v-model="form.email" rules="email"></text-input>
                        <b-form-textarea v-model="form.note" placeholder="Notes about this contact..." rows="3"></b-form-textarea>
                        <b-form-checkbox v-model="form.shared" class="text-center" switch>Share Contact Across All Sites</b-form-checkbox>
                        <b-form-group label="Phone Numbers" label-for="numbers" class="mt-2">
                            <div class="row mt-2" v-for="(data, key) in form.phones" :key="key">
                                <div class="col-sm-3 col-4 pr-1">
                                    <b-form-select v-model="data.type" :options="phone_types" value-field="description" text-field="description"></b-form-select>
                                </div>
                                <div class="col-sm-5 col-4 px-1">
                                    <vue-phone-number-input v-model="data.number" no-country-selector ></vue-phone-number-input>
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
                        <submit-button button_text="Create Contact" :submitted="submitted"></submit-button>
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
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id: this.cust_id,
                    name:    '',
                    title:   '',
                    email:   '',
                    note:    '',
                    shared:  false,
                    phones: [
                        {
                            type:      'Mobile',
                            number:    '',
                            extension: '',
                        },
                    ]
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
                this.submitted = true;
                this.loading   = true;
                this.$inertia.post(route('customers.contacts.store'), this.form, {onFinish: () => {
                    this.$refs['new-contact-modal'].hide();
                    this.form = {
                        cust_id: this.cust_id,
                        name:    '',
                        email:   '',
                        shared:  false,
                        phones: [
                            {
                                type:      'Mobile',
                                number:    '',
                                extension: '',
                            },
                        ]
                    };
                    this.$emit('completed');
                }});
            },
            addRow()
            {
                this.form.phones.push({
                    type:      'Mobile',
                    readable:  '',
                    extension: '',
                });
            },
            removeRow(key)
            {
                this.form.phones.splice(key, 1);
            }
        },
    }
</script>

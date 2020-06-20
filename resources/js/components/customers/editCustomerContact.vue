<template>
    <div>
        <i v-if="contact_data" class="far fa-edit pointer" title="Edit" v-b-tooltip @click="openModal"></i>
        <b-button v-else variant="primary" pill size="sm" @click="openModal">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Add Contact
        </b-button>
        <b-modal :title="button_msg" ref="customer-contact-modal" size="lg" hide-footer>
            <div v-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Form...</h4>
            </div>
            <b-overlay v-else :show="submitted">
                <template v-slot:overlay>
                    <atom-spinner
                        :animation-duration="1000"
                        :size="60"
                        color="#ff1d5e"
                        class="mx-auto"
                    />
                    <h4 class="text-center">Processing...</h4>
                </template>
                <b-form @submit="validateForm" ref="customer-contact-form" novalidate :validated="validated">
                    <b-form-group label="Name" label-for="name">
                        <b-form-input id="name" v-model="form.name" type="text" required autofocus placeholder="Enter Contact Name"></b-form-input>
                        <b-form-invalid-feedback>You must enter a name for the contact</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Email Address" label-for="email">
                        <b-form-input id="email" v-model="form.email" type="email" placeholder="Enter Contact Email Address"></b-form-input>
                        <b-form-invalid-feedback>Please use a valid email format</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-checkbox v-model="form.shared" switch v-if="linked_site">Share Contact Across All Sites</b-form-checkbox>
                    <b-form-group label="Phone Numbers" label-for="numbers" class="mt-2">
                        <div class="row mt-2" v-for="(data, key) in form.customer_contact_phones" :key="key">
                            <div class="col-sm-3 col-4 pr-1">
                                <b-form-select v-model="data.phone_type_id" :options="phone_types" value-field="phone_type_id" text-field="description"></b-form-select>
                            </div>
                            <div class="col-sm-5 col-4 px-1">
                                <vue-phone-number-input v-model="data.readable" no-country-selector ></vue-phone-number-input>
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
                    <form-submit
                        class="mt-3"
                        :button_text="button_msg"
                        :submitted="submitted"
                    ></form-submit>
                </b-form>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            },
            contact_data: {
                type:     Object,
                required: false,
                default:  null,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                validated: false,
                phone_types: [],
                form: {
                    cust_id: this.cust_id,
                    name:    null,
                    email:   null,
                    shared:  false,
                    customer_contact_phones: [
                        {
                            phone_type_id: 1,
                            readable:  '',
                            extension: '',
                        },
                    ],
                }
            }
        },
        mounted() {
             this.getPhoneTypes();
        },
        computed: {
             button_msg()
            {
                return this.contact_data ? 'Edit Contact' : 'Add Contact';
            },
            button_variant()
            {
                return this.contact_data ? 'warning' : 'primary';
            },
            edit_cont()
            {
                return this.contact_data ? true : false;
            },
        },
        methods: {
            openModal()
            {
                if(this.edit_cont)
                {
                    this.form = {
                        cust_id: this.cust_id,
                        name:    this.contact_data.name,
                        email:   this.contact_data.email,
                        shared:  this.contact_data.shared,
                        customer_contact_phones: this.contact_data.customer_contact_phones,
                    }
                }
                this.$refs['customer-contact-modal'].show();
            },
            addRow()
            {
                this.form.customer_contact_phones.push({
                    phone_type_id: 1,
                    readable: '',
                    extension: '',
                });
            },
            removeRow(key)
            {
                this.form.customer_contact_phones.splice(key, 1);
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['customer-contact-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     if(this.edit_cont)
                     {
                         axios.put(this.route('customer.contacts.update', this.contact_data.cont_id), this.form)
                             .then(res => {
                                 this.submitted = false;
                                 this.$emit('contact-updated');
                                 this.$refs['customer-contact-modal'].hide();
                             }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                     else
                     {
                         axios.post(this.route('customer.contacts.store'), this.form)
                             .then(res => {
                                 this.submitted = false;
                                 this.$emit('contact-updated');
                                 this.$refs['customer-contact-modal'].hide();
                             }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                }
            },
            getPhoneTypes()
            {
                axios.get(this.route('customer.contacts.index'))
                    .then(res => {
                        this.phone_types = res.data;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        }
    }
</script>

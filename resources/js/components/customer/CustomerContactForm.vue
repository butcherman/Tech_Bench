<template>
    <b-modal :title="modalTitle" hide-footer centered ref="contactModal" size="lg">
        <div v-if="error">
            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Something bad happened...</h5>
        </div>
        <div v-else-if="loading">
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Loading Form</h4>
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
            <b-form @submit="validateForm" novalidate :validated="validated" ref="contactForm">
                <b-form-group label="Name" label-for="name">
                    <b-form-input id="name" v-model="form.name" type="text" required autofocus placeholder="Enter Contact Name"></b-form-input>
                    <b-form-invalid-feedback>You must enter a name for the contact</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Email Address" label-for="email">
                    <b-form-input id="email" v-model="form.email" type="email" placeholder="Enter Contact Email Address"></b-form-input>
                    <b-form-invalid-feedback>Please use a valid email format</b-form-invalid-feedback>
                </b-form-group>
                <b-form-checkbox v-model="form.shared" switch v-if="linked">Share Contact Across All Sites</b-form-checkbox>
                <b-form-group label="Phone Numbers" label-for="numbers" class="mt-2">
                    <div class="row mt-2" v-for="(data, key) in form.customer_contact_phones" :key="key">
                        <div class="col-3">
                            <b-form-select v-model="data.phone_type_id" :options="phoneTypes" ></b-form-select>
                        </div>
                        <div class="col-5">
                            <vue-phone-number-input v-model="data.readable" no-country-selector ></vue-phone-number-input>
                            <b-form-invalid-feedback>Please use a valid phone number format</b-form-invalid-feedback>
                        </div>
                        <div class="col-2">
                            <b-form-input size="sm" type="text" v-model="data.extension" placeholder="Extension"></b-form-input>
                       </div>
                       <div class="col-1">
                           <b-button variant="danger" title="Remove Number" size="sm" pill v-b-tooltip.hover @click="removeRow(key)"><i class="fas fa-phone-slash"></i></b-button>
                       </div>
                    </div>
                </b-form-group>
                <b-button size="sm" variant="primary" class="float-right mb-3" pill @click="addRow">Add Row</b-button>
                <form-submit
                    class="mt-3"
                    :button_text="btnText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-overlay>
    </b-modal>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type: Number,
                required: true,
            },
            linked: {
                type: Boolean,
                required: false,
                default: false,
            }
        },
        data() {
            return {
                error: false,
                loading: false,
                submitted: false,
                // btnText: '',
                validated: false,
                newCont: false,
                phoneTypes: {},
                form: {
                    cust_id: this.cust_id,
                    name: null,
                    email: null,
                    shared: false,
                    customer_contact_phones: {
                        phone_type_id: 1,
                        readable: '',
                        extension: '',
                    },
                },
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
             modalTitle()
             {
                 return this.newCont ? 'Create New Contact' : 'Edit Contact';
             },
             btnText()
             {
                 return this.newCont ? 'Create Contact' : 'Update Contact';
             }
        },
        watch: {
             //
        },
        methods: {
            initNewContact()
            {
                console.log('new contact');
                this.getPhoneTypes();
                this.newCont = true;
                this.form.name = null,
                this.form.email = null,
                this.form.shared = false,
                this.form.customer_contact_phones = [{
                    phone_type_id: 1,
                    readable: '',
                    extension: '',
                }];
                this.$refs['contactModal'].show();
            },
            initEditContact(data)
            {
                this.getPhoneTypes();
                this.newCont = false;
                this.$refs['contactModal'].show();
                this.form = data;
            },
            getPhoneTypes()
            {
                if(!this.phoneTypes.length)
                {
                    this.loading = true;
                    axios.get(this.route('customer.contacts.index'))
                        .then(res => {
                            this.phoneTypes = res.data;
                            this.loading = false;
                        }).catch(error => this.error = true);
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                console.log(this.form);
                if(this.$refs.contactForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    if(this.newCont)
                    {
                        console.log('new contact submitted');
                        axios.post(this.route('customer.contacts.store'), this.form)
                        .then(res => {
                            // this.$refs.newContactModal.hide();
                            // this.getContacts();
                            this.$emit('completed');
                            this.$refs['contactModal'].hide();
                            this.submitted = false;
                        }).catch(error => console.log(error));
                    }
                    else
                    {
                        axios.put(this.route('customer.contacts.update', this.form.cust_id), this.form)
                            .then(res => {
                                this.$emit('completed');
                                this.$refs['contactModal'].hide();
                                this.submitted = false;
                            }).catch(error => alert(error));
                    }
                }
            },
            addRow()
            {
                console.log('add row triggered');
                this.form.customer_contact_phones.push({
                    phone_type_id: 1,
                    readable: '',
                    extension: '',
                });
                console.log(this.form.customer_contact_phones);
            },
            removeRow(id)
            {
                this.form.customer_contact_phones.splice(id, 1);
            }
        }
    }
</script>

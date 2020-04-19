<template>
    <div>
        <div v-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Loading Contacts</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <vue-good-table v-else
            ref="customer-contacts-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :isLoading.sync="isLoading"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Contacts</h4>
            </div>
            <div slot="table-actions">
                <b-button variant="info block" v-b-modal.new-contact-modal><i class="fas fa-plus" aria-hidden="true"></i> Add Contact</b-button>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'phone'">
                    <a :href="'tel:'+num.number+','+num.extension" v-for="num in data.row.customer_contact_phones" :key="num.id" :title="'Call '+num.type_name" v-b-tooltip class="d-block">
                        <i :class="num.type_icon" class="text-muted"></i>
                        {{num.readable}} <span v-if="num.extension">Ext-{{num.extension}}</span>
                    </a>
                </span>
                <span v-else-if="data.column.field == 'email'">
                    <a :href="'mailto:'+data.row.email" v-if="data.row.email">
                        <i class="far fa-envelope text-muted"></i>
                        {{data.row.email}}
                    </a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="far fa-edit pointer" title="Edit" v-b-tooltip @click="loadEditData(data.row)"></i>
                    <i class="far fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteContact(data.row.cont_id)"></i>
                    <a :href="route('customer.contacts.edit', data.row.cont_id)" title="Download" v-b-tooltip class="text-muted"><i class="far fa-address-card"></i></a>
                </span>
            </template>
            <template slot="loadingContent">
                <div class="text-center">Loading Customers</div>
                <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
            </template>
        </vue-good-table>
        <b-modal id="new-contact-modal" title="Customer Contacts" hide-footer centered ref="newContactModal" size="lg">
            <b-form @submit="submitForm" novalidate :validated="validated" ref="newContactForm">
                <b-form-group label="Name" label-for="name">
                    <b-form-input id="name" v-model="form.name" type="text" required autofocus placeholder="Enter Contact Name"></b-form-input>
                    <b-form-invalid-feedback>You must enter a name for the contact</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Email Address" label-for="email">
                    <b-form-input id="email" v-model="form.email" type="email" placeholder="Enter Contact Email Address"></b-form-input>
                    <b-form-invalid-feedback>Please use a valid email format</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Phone Numbers" label-for="numbers">
                   <div class="row pad-top" v-for="n in totalNums" :key="n.value">
                       <div class="col-3">
                           <b-form-select v-model="form.numbers.type[n-1]" :options="phone_types" ></b-form-select>
                       </div>
                       <div class="col-6">
                            <vue-phone-number-input v-model="form.numbers.number[n-1]" no-country-selector error></vue-phone-number-input>
                            <b-form-invalid-feedback>Please use a valid phone number format</b-form-invalid-feedback>
                       </div>
                       <div class="col-3">
                            <b-form-input type="text" v-model="form.numbers.ext[n-1]" placeholder="Extension"></b-form-input>
                       </div>
                    </div>
                </b-form-group>
                <div class="float-right text-primary pointer" @click="addRow">Add Row</div>
                <b-button type="submit" variant="primary" class="pad-top" block :diable="button.disable">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                    {{button.text}}
                </b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
            'phone_types',
            'linked',
        ],
        data () {
            return {
                loading: true,
                error: false,
                isLoading: false,
                validated: false,
                edit: false,
                totalNums: 1,
                table: {
                    columns: [
                        {
                            label: 'Name',
                            field: 'name',
                        },
                        {
                            label: 'Phone Number',
                            field: 'phone',
                        },
                        {
                            label: 'Email',
                            field: 'email',
                        },
                        {
                            label: 'Actions',
                            field: 'actions'
                        }
                    ],
                    rows: [],
                },
                form: {
                    cust_id: this.cust_id,
                    name: '',
                    email: '',
                    numbers: {
                        type: [2],
                        number: [],
                        ext: [],
                    },
                    shared: false,
                },
                button: {
                    text: 'Submit New Contact',
                    disable: false,
                }
            }
        },
        created()
        {
            // this.getContacts();
            this.$root.$on('bv::modal::hide', (bvEvent, modalID) => {
                this.resetForm();
            });
        },
        methods: {
            addRow()
            {
                this.totalNums += 1;
                this.form.numbers.type[this.totalNums-1] = 2;
            },
            getContacts()
            {
                this.isLoading = true;
                axios.get(this.route('customer.contacts.show', this.cust_id))
                    .then(res => {
                        this.table.rows = res.data;
                        this.loading = false;
                    }).catch(error => this.error = true);
            },
            loadEditData(cont)
            {
                this.form.name = cont.name;
                this.form.email = cont.email;
                this.form.shared = cont.shared;
                this.button.text = 'Update Contact';
                this.edit = cont.cont_id;
                for(var i=0; i < cont.customer_contact_phones.length; i++)
                {
                    this.form.numbers.type[i]   = cont.customer_contact_phones[i].phone_type_id;
                    this.form.numbers.number[i] = cont.customer_contact_phones[i].phone_number;
                    this.form.numbers.ext[i]    = cont.customer_contact_phones[i].extension;
                    this.totalNums = i+1;
                }
                this.$refs.newContactModal.show();
            },
            submitForm(e)
            {
                e.preventDefault();
                if(this.$refs.newContactForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.button.disable = true;
                    this.button.text = 'Processing...';
                    if(!this.edit)
                    {
                        this.addContact();
                    }
                    else
                    {
                        this.editContact();
                    }
                }
            },
            resetForm()
            {
                this.edit = false;
                this.form.name = '';
                this.form.email = '';
                this.form.numbers.type = [2];
                this.form.numbers.number = [];
                this.form.numbers.ext = [];
                this.totalNums = 1;
                this.button.disable = false;
                this.button.text = 'Submit New Contact';
            },
            addContact()
            {
                axios.post(this.route('customer.contacts.store'), this.form)
                        .then(res => {
                            this.$refs.newContactModal.hide();
                            this.getContacts();
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            editContact()
            {
                axios.put(this.route('customer.contacts.update', this.edit), this.form)
                        .then(res => {
                            this.$refs.newContactModal.hide();
                            this.getContacts();
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            deleteContact(id)
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete this contact.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        this.isLoading = true;
                        axios.delete(this.route('customer.contacts.destroy', id))
                            .then(res => {
                                this.resetForm();
                                this.getContacts();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                });
            }
        },
    }
</script>

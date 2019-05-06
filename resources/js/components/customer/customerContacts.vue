<template>
    <div>
        <b-table :fields="columns" :items="contacts" :primary-key="contacts.cont_id" show-empty>
            <template slot="number" slot-scope="data">
              <span v-for="num in data.item.numbers">
                  <i :class="'fa '+num.class"></i> {{num.type}} - <a :href="'tel:'+num.number+','+num.extension">{{num.num_formatted}}</a><br />
              </span>
            </template>
            <span slot="email" slot-scope="data"><a :href="'mailto:'+data.item.email">{{data.item.email}}</a></span>
            <template slot="actions" slot-scope="data">
                <i class="fa fa-pencil pointer" v-b-tooltip.hover title="Edit Contact" @click="editContact(data.item)"></i> 
                <click-confirm class="d-inline">
                    <i class="fa fa-trash pointer" v-b-tooltip.hover title="Delete Contact" @click="removeContact(data.item.cont_id)"></i> 
                </click-confirm>
                <a :href="edit_contact_route.replace(':id', data.item.cont_id)+'/edit'" v-b-tooltip.hover title="Download Contact" class="text-muted"><i class="fa fa-address-card pointer"></i></a>
            </template>
            <template slot="empty">
                <h3 class="text-center">No Contacts</h3>
            </template>
        </b-table>
        <div class="row justify-content-center pad-top">
            <div class="col-md-4">
                <b-button variant="info" block v-b-modal.new-contact-modal>Add Contact</b-button>
            </div>
        </div>
        <b-modal title="Add Contact" id="new-contact-modal" ref="addContactModal" hide-footer>
            <b-form @submit="submitNewContact">
                <b-form-group
                    label="Contact Name"
                    label-for="contact-name"    
                >
                    <b-form-input
                        id="contact-name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Enter Contacts Name"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Email Address"
                    label-for="contact-email"    
                >
                    <b-form-input
                        id="contact-email"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter Contacts Email Address"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Phone Numbers"
                    label-for="numbers"
                >
                   <div class="row pad-top" v-for="n in range">
                       <div class="col-3">
                           <b-form-select v-model="form.numbers.type[n-1]" :options="JSON.parse(phone_types)" required></b-form-select>
                       </div>
                       <div class="col-6">
                            <b-form-input type="text" v-model="form.numbers.number[n-1]" placeholder="Number"></b-form-input>
                       </div>
                       <div class="col-3">
                            <b-form-input type="text" v-model="form.numbers.ext[n-1]" placeholder="Extension"></b-form-input>
                       </div>
                    </div>
                </b-form-group>
                <div class="float-right text-primary pointer" @click="addRow">Add Row</div>
                <b-button type="submit" variant="info" class="pad-top" block>Submit New Contact</b-button>
            </b-form>
        </b-modal>
        <b-modal title="Edit Contact" id="edit-contact-modal" ref="editContactModal" hide-footer>
            <b-form @submit="submitEditContact">
                <b-form-group
                    label="Contact Name"
                    label-for="contact-name"    
                >
                    <b-form-input
                        id="contact-name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Enter Contacts Name"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Email Address"
                    label-for="contact-email"    
                >
                    <b-form-input
                        id="contact-email"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter Contacts Email Address"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Phone Numbers"
                    label-for="numbers"
                >
                   <div class="row pad-top" v-for="n in range">
                       <div class="col-3">
                           <b-form-select v-model="form.numbers.type[n-1]" :options="JSON.parse(phone_types)" required></b-form-select>
                       </div>
                       <div class="col-6">
                            <b-form-input type="text" v-model="form.numbers.number[n-1]" placeholder="Number"></b-form-input>
                       </div>
                       <div class="col-3">
                            <b-form-input type="text" v-model="form.numbers.ext[n-1]" placeholder="Extension"></b-form-input>
                       </div>
                    </div>
                </b-form-group>
                <div class="float-right text-primary pointer" @click="addRow">Add Row</div>
                <b-button type="submit" variant="info" class="pad-top" block>Update Contact Contact</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
            'phone_types',
            'get_contacts_route',
            'new_contact_route',
            'edit_contact_route',
        ],
        data () {
            return {
                columns: [
                    {
                        label: 'Name',
                        key:   'name',
                    },
                    {
                        label: 'Phone Number',
                        key:   'number',
                    },
                    {
                        label: 'Email',
                        key:   'email',
                    },
                    {
                        label: 'Actions',
                        key:   'actions',
                    },
                ],
                contacts: [],
                form: {
                    custID: this.cust_id,
                    name:  '',
                    email: '',
                    numbers: {
                        type:   [2],
                        number: [],
                        ext:    [],
                    },
                },
                range: 1,
            }
        },
        created() 
        {
            this.getContacts();
        },
        methods: {
            getContacts()
            {
                axios.get(this.get_contacts_route)
                    .then(res => {
                        this.contacts = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            submitNewContact(e)
            {
                e.preventDefault();
                
                axios.post(this.new_contact_route, this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.$refs.addContactModal.hide();
                            this.resetContactForm();
                            this.getContacts();
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            submitEditContact(e)
            {
                e.preventDefault()
                
                axios.put(this.edit_contact_route.replace(':id', this.form.cont_id), this.form)
                    .then(res => {  
                        if(res.data.success == true)
                        {
                            this.$refs.editContactModal.hide();
                            this.resetContactForm();
                            this.getContacts();
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            addRow()
            {
                this.range += 1;
            },
            editContact(id)
            {
                this.$refs.editContactModal.show();
                this.form.cont_id = id.cont_id;
                this.form.name    = id.name;
                this.form.email   = id.email;
                this.range        = id.numbers.length;
                
                var type   = [];
                var number = [];
                var ext    = [];
                
                id.numbers.forEach(function(item)
                {
                    type.push(item.type_id);
                    number.push(item.number);
                    ext.push(item.ext);
                });
                
                this.form.numbers.type   = type;
                this.form.numbers.number = number;
                this.form.numbers.ext    = ext;
            },
            removeContact(id)
            {
                axios.delete(this.edit_contact_route.replace(':id', id))
                    .then(res => {
                        console.log(res);
                        this.getContacts();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            resetContactForm()
            {
                this.form.name           = '';
                this.form.email          = '';
                this.form.numbers.type   = [2];
                this.form.numbers.number = [];
                this.form.numbers.ext    = [];
                this.range = 1;
            }
        }
    }
</script>

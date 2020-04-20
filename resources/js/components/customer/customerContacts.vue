<template>
    <div class="card">
        <div class="card-header">
            Customer Contacts:
            <b-button variant="primary" pill size="sm" class="float-right" @click="newContactForm">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add Contact
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Contacts...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Contacts</h4>
            </div>
            <vue-good-table v-else
                ref="customer-contacts-table"
                styleClass="vgt-table bordered w-100"
                :columns="table.columns"
                :rows="table.rows"
            >
                <template slot="emptystate">
                    <h4 class="text-center">No Contacts</h4>
                </template>
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
                        <i class="far fa-edit pointer" title="Edit" v-b-tooltip @click="editContactForm(data.row)"></i>
                        <a :href="route('customer.contacts.edit', data.row.cont_id)" title="Download" v-b-tooltip class="text-muted"><i class="far fa-address-card"></i></a>
                        <i class="far fa-trash-alt pointer" title="Delete" v-b-tooltip @click="deleteContact(data.row.cont_id)"></i>
                    </span>
                </template>
            </vue-good-table>
        </div>
        <contact-form ref="customer-contact-form" @completed="getContacts" :cust_id="this.cust_id" :linked="isLinked"></contact-form>
    </div>
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
            isLinked: this.linked,
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
            }
        }
    },
    mounted() {
        this.getContacts();
    },
    watch: {
        linked()
        {
            this.isLinked = this.linked;
        }
    },
    methods: {
        newContactForm()
        {
            this.$refs['customer-contact-form'].initNewContact();
        },
        editContactForm(cont)
        {
            this.$refs['customer-contact-form'].initEditContact(cont);
        },
        deleteContact(contID)
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
                        this.loading = true;
                        axios.delete(this.route('customer.contacts.destroy', contID))
                            .then(res => {
                                this.getContacts();
                            }).catch(error => alert(error));
                    }
                });
        },
        getContacts()
        {
            this.loading = true;
            axios.get(this.route('customer.contacts.show', this.cust_id))
                .then(res => {
                    this.table.rows = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },

    }
}
</script>

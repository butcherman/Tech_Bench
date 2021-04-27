<template>
    <div>
        <div class="card-title">
            Contacts:
            <new-contact-modal
                :cust_id="cust_id"
                @completed="getContacts"
            ></new-contact-modal>
        </div>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader text="Loading Contacts..."></atom-loader>
            </template>
            <b-table striped :items="contacts.data" :fields="contacts.fields" empty-text="No Contacts" responsive show-empty>
                <template #row-details="data">
                    <b-table-simple class="w-100">
                        <b-thead>
                            <b-tr>
                                <b-th>Email</b-th>
                                <b-th>Phones</b-th>
                                <b-th>Note:</b-th>
                            </b-tr>
                        </b-thead>
                        <b-tbody>
                            <b-tr>
                                <b-td><a :href="'mailto:'+data.item.email" title="Click to Send Email" v-b-tooltip.hover>{{data.item.email}}</a></b-td>
                                <b-td>
                                    <div v-for="phone in data.item.customer_contact_phone" :key="phone.id">
                                        <a :href="'tel:'+phone.phone_number+','+phone.extension" :title="phone.phone_number_type.description" v-b-tooltip.hover>
                                            <i :class="phone.phone_number_type.icon_class"></i>
                                            {{phone.formatted}}
                                            <span v-if="phone.extension">
                                                Ext. {{phone.extension}}
                                            </span>
                                        </a>
                                    </div>
                                </b-td>
                                <b-td>{{data.item.note}}</b-td>
                            </b-tr>
                        </b-tbody>
                    </b-table-simple>
                </template>
                <template #cell(details)="data">
                    <b-button @click="data.toggleDetails" variant="info">
                        {{data.detailsShowing ? 'Hide' : 'Show'}} Details
                    </b-button>
                </template>
                <template #cell(actions)="data">
                    <edit-contact-modal :cust_id="cust_id" :contact_data="data.item" @completed="getContacts"></edit-contact-modal>
                    <b-button :href="route('customers.contacts.download', data.item.cont_id)" pill size="sm" variant="light" title="Download Contact" v-b-tooltip.hover>
                        <i class="far fa-address-card"></i>
                    </b-button>
                    <b-button pill size="sm" variant="light" title="Delete Contact" v-b-tooltip.hover @click="deleteContact(data.item.cont_id)">
                        <i class="far fa-trash-alt"></i>
                    </b-button>
                </template>
            </b-table>
        </b-overlay>
    </div>
</template>

<script>
    import EditContactModal from './Contacts/editContactModal.vue';
    import newContactModal  from './Contacts/newContactModal.vue';

    export default {
        components: { newContactModal, EditContactModal },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            customer_contacts: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                contacts: {
                    fields: [
                        {
                            key:     'details',
                            label:   '',
                            sortable: false,
                        },
                        {
                            key:     'name',
                            label:   'Name',
                            sortable: true,
                        },
                        {
                            key:     'title',
                            label:   'Title',
                            sortable: true,
                        },
                        {
                            key:     'actions',
                            label:   '',
                            sortable: false,
                        }
                    ],
                    data: this.customer_contacts,
                }
            }
        },
        methods: {
            getContacts()
            {
                this.loading = true;
                axios.get(this.route('customers.contacts.show', this.cust_id))
                    .then(res => {
                        this.contacts.data = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteContact(cont_id)
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
                        axios.delete(this.route('customers.contacts.destroy', cont_id))
                            .then(() => {
                                this.getContacts();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                });
            }
        },
    }
</script>

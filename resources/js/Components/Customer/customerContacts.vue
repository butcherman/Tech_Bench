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
            <b-table striped :items="contacts.data" :fields="contacts.fields" empty-text="No Contacts">
                <template #cell(phone)="data">
                    <div v-for="ph in data.item.customer_contact_phone" :key="ph.id">
                        <a :href="'tel:'+ph.phone_number+','+ph.extnesion" :title="ph.phone_number_type.description" v-b-tooltip.hover>
                            <i :class="ph.phone_number_type.icon_class"></i>
                            {{ph.formatted}}
                        </a>
                    </div>
                </template>
                <template #cell(email)="data">
                    <a :href="'mailto:'+data.item.email" v-if="data.item.email">
                        <i class="far fa-envelope text-muted"></i>
                        {{data.item.email}}
                    </a>
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
                            key:     'name',
                            label:   'Name',
                            sortable: true,
                        },
                        {
                            key:     'phone',
                            label:   'Phone Number',
                            sortable: false,
                        },
                        {
                            key:     'email',
                            label:   'Email',
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
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            getContacts()
            {
                console.log('get contacts');
                this.loading = true;
                axios.get(this.route('customers.contacts.show', this.cust_id))
                    .then(res => {
                        console.log(res);
                        this.contacts.data = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteContact(cont_id)
            {
                console.log(cont_id);
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
                            .then(res => {
                                this.getContacts();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                });
            }
        },
    }
</script>

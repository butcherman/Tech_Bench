<template>
    <b-table striped :items="contacts" :fields="cont.fields" empty-text="No Contacts" responsive show-empty>
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
        <template #cell(name)="data">
            <i v-if="data.item.shared" class="fas fa-share" title="Contact Shared Across Sites" v-b-tooltip.hover></i>
            {{data.item.name}}
        </template>
        <template #cell(details)="data">
            <b-button @click="data.toggleDetails" variant="info" size="sm" pill>
                <i class="fas" :class="data.detailsShowing ? 'fa-eye-slash' : 'fa-eye'" :title="data.detailsShowing ? 'Hide Details' : 'Show Details'" v-b-tooltip.hover></i>
            </b-button>
        </template>
        <template #cell(actions)="data">
            <b-button
                :href="route('customers.contacts.download', data.item.cont_id)"
                size="sm"
                variant="light"
                title="Download Contact"
                pill
                v-b-tooltip.hover
            >
                <i class="far fa-address-card"></i>
            </b-button>
            <edit-contact
                v-if="permissions.update"
                :details="data.item"
                :allow_share="allow_share"
            ></edit-contact>
            <b-button
                v-if="permissions.delete"
                size="sm"
                variant="light"
                title="Delete Contact"
                pill
                v-b-tooltip.hover
                @click="deleteContact(data.item.cont_id)"
            >
                <i class="far fa-trash-alt"></i>
            </b-button>
        </template>
    </b-table>
</template>

<script>
import EditContact from './editContact.vue'

    export default {
        components: { EditContact },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            contacts: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                cont: {
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
                }
            }
        },
        methods: {
            /**
             * Soft delete a contact
             */
            deleteContact(contact)
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
                        this.$inertia.delete(route('customers.contacts.destroy', contact), {
                            onFinish: () => {
                                //
                            }
                        });
                    }
                });
            }
        },
    }
</script>

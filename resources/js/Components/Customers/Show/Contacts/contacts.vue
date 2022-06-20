<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title clearfix">
                Contacts
                <new-contact v-if="customerStore.userPerm.contacts.create" />
            </div>
            <b-table
                striped
                :items="customerStore.contacts"
                :fields="fields"
                empty-text="No Contacts"
                responsive
                show-empty
            >
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
                                <b-td>
                                    <a
                                        :href="`mailto:${data.item.email}`"
                                        title="Click to Send Email"
                                        v-b-tooltip.hover
                                    >
                                        {{data.item.email}}
                                    </a>
                                </b-td>
                                <b-td>
                                    <div
                                        v-for="phone in data.item.customer_contact_phone"
                                        :key="phone.id"
                                    >
                                        <a
                                            :href="`tel:${phone.phone_number},${phone.extension}`"
                                            :title="phone.phone_number_type.description"
                                            v-b-tooltip.hover
                                        >
                                            <i :class="phone.phone_number_type.icon_class"></i>
                                            {{phone.formatted}}
                                            <span v-if="phone.extension">
                                                Ext. {{phone.extension}}
                                            </span>
                                        </a>
                                    </div>
                                </b-td>
                                <b-td>
                                    {{data.item.note}}
                                </b-td>
                            </b-tr>
                        </b-tbody>
                    </b-table-simple>
                </template>
                <template #cell(details)="data">
                    <b-button
                        size="sm"
                        variant="info"
                        pill
                        @click="data.toggleDetails"
                    >
                        <i
                            class="fas"
                            :class="data.detailsShowing ? 'fa-eye-slash' : 'fa-eye'"
                            :title="data.detailsShowing ? 'Hide Details' : 'Show Details'"
                            v-b-tooltip.hover
                        />
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
                        v-if="customerStore.allowPermission('contacts', 'update')"
                        :contIndex="data.index"
                    />
                    <delete-contact
                        v-if="customerStore.allowPermission('contacts', 'delete')"
                        :contId="data.item.cont_id"
                    />
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                fields: [
                    {
                        key     : 'details',
                        label   : '',
                        sortable: false,
                    },
                    {
                        key     : 'name',
                        label   : 'Name',
                        sortable: true,
                    },
                    {
                        key     : 'title',
                        label   : 'Title',
                        sortable: true,
                    },
                    {
                        key     : 'actions',
                        label   : '',
                        sortable: false,
                    },
                ]
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
    }
</script>

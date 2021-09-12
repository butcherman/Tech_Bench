<template>
    <div>
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <h3>
                    <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
                    {{details.name}}
                    <small>
                        <i v-if="details.child_count > 0" class="fas fa-link pointer text-secondary" title="Show Linked Customers" v-b-tooltip.hover v-b-modal.linked-customers-modal></i>
                    </small>
                </h3>
                <h5 v-if="details.dba_name">AKA - {{details.dba_name}}</h5>
                <h5 v-if="details.parent_id">Child Site of - <inertia-link :href="route('customers.show', details.parent.slug)">{{details.parent.name}}</inertia-link></h5>
                <address>
                    <div class="float-left">
                        <i class="fas fa-map-marked-alt text-muted"></i>
                    </div>
                    <a :href="map_url" target="_blank" id="addr-span" class="float-left ml-2" title="Click for Google Maps" v-b-tooltip.hover>
                        {{details.address}}<br />
                        {{details.city}}, {{details.state}} &nbsp;{{details.zip}}
                    </a>
                </address>
            </div>
            <div class="col-md-4 mt-md-0 mt-4">
                <div class="float-md-right">
                    <edit-details :details="details"></edit-details>
                    <manage-customer
                        v-if="user_data.manage"
                        :cust_id="details.cust_id"
                        :can_deactivate="user_data.deactivate"
                        :linked="details.parent_id > 0 ? true : false"
                        :is_parent="details.child_count > 0 ? true : false"
                    ></manage-customer>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Equipment:
                            <new-equipment
                                v-if="user_data.equipment.create"
                                :cust_id="details.cust_id"
                                :existing_equip="equipIdList"
                                :allow_share="allowShare"
                            ></new-equipment>
                        </div>
                        <equipment
                            :cust_id="details.cust_id"
                            :equipment="details.customer_equipment"
                            :permissions="user_data.equipment"
                            :allow_share="allowShare"
                        ></equipment>
                    </div>
                </div>
            </div>
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Contacts:
                            <new-contact
                                v-if="user_data.contacts.create"
                                :cust_id="details.cust_id"
                                :allow_share="allowShare"
                            ></new-contact>
                        </div>
                        <contacts
                            :cust_id="details.cust_id"
                            :contacts="details.customer_contact"
                            :permissions="user_data.contacts"
                            :allow_share="allowShare"
                        ></contacts>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Notes:
                            <new-note
                                v-if="user_data.notes.create"
                                :cust_id="details.cust_id"
                                :allow_share="allowShare"
                            ></new-note>
                        </div>
                        <notes
                            :cust_id="details.cust_id"
                            :notes="details.customer_note"
                            :permissions="user_data.notes"
                            :allow_share="allowShare"
                        ></notes>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="linked-customers-modal" :title="'Customers linked to '+details.name" @show="getLinkedCustomers">
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader></atom-loader>
                </template>
                <b-list-group>
                    <b-list-group-item v-for="l in linked" :key="l.cust_id" class="text-center"><inertia-link :href="route('customers.show', l.slug)">{{l.name}}</inertia-link></b-list-group-item>
                </b-list-group>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import App            from '../../Layouts/app';
    import editDetails    from '../../Components/Customers/editDetails.vue';
    import ManageCustomer from '../../Components/Customers/manageCustomer.vue';

    import Equipment      from '../../Components/Customers/Equipment/equipment.vue';
    import NewEquipment   from '../../Components/Customers/Equipment/newEquipment.vue';

    import Contacts       from '../../Components/Customers/Contacts/contacts.vue';
    import NewContact     from '../../Components/Customers/Contacts/newContact.vue';

    import Notes          from '../../Components/Customers/Notes/notes.vue';
    import NewNote        from '../../Components/Customers/Notes/newNote.vue';

    export default {
        components: {
            editDetails,
            ManageCustomer,
            Equipment,
            NewEquipment,
            Contacts,
            NewContact,
            Notes,
            NewNote,
        },
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            },
            user_data: {
                type:    Object,
                required: true,
            }
        },
        data() {
            return {
                is_fav:    this.user_data.fav,
                linked:    [],
                loading:   false,
                equipment: this.details.parent_equipment.concat(this.details.customer_equipment),
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
            map_url()
            {
                return 'https://maps.google.com/?q='+encodeURI(this.details.address+','+this.details.city+','+this.details.state);
            },
            bookmark_class()
            {
                return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks'
            },
            equipIdList()
            {
                var list = [];
                this.equipment.forEach(function(item)
                {
                    list.push(item.equip_id);
                });

                return list;
            },
            allowShare()
            {
                return this.details.parent_id !== null || this.details.child_count > 0 ? true : false;
            }
        },
        watch: {
             //
        },
        methods: {
            toggleFav()
            {
                var form = {
                    cust_id: this.details.cust_id,
                    state:   !this.is_fav,
                }

                axios.post(this.route('customers.bookmark'), form)
                    .then(res => {
                        this.is_fav = !this.is_fav;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            getLinkedCustomers()
            {
                if(this.linked.length == 0)
                {
                    this.loading = true;
                    axios.get(this.route('customers.get-linked', this.details.cust_id))
                        .then(res => {
                            this.linked  = res.data;
                            this.loading = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        },
        metaInfo: {
            title: 'Customer Details',
        }
    }
</script>

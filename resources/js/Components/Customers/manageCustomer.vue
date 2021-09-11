<template>
    <b-button class="mt-1" pill variant="danger" size="sm" v-b-modal.manage-customer-modal @click="getDeletedItems()" title="Manage Customer" v-b-tooltip.hover>
        <i class="fas fa-tasks"></i>
        Manage
        <b-modal
            id="manage-customer-modal"
            ref="manage-customer-modal"
            title="Manage Customer"
            hide-footer
        >
            <b-overlay :show="loading" no-center>
                <template #overlay>
                    <atom-loader text="Loading Data..."></atom-loader>
                </template>
                <div>
                    <div class="mt-2" v-if="deleted['equipment'] && deleted['equipment'].length > 0">
                        <h5 class="text-center">Deleted Equipment</h5>
                        <ul class="list-group">
                            <li class="list-group-item text-center" v-for="item in deleted['equipment']" :key="item.cust_equip_id">
                                <i class="fas fa-trash-restore text-success pointer float-left mr-2" title="Restore" v-b-tooltip.hover @click="restore('equipment', item.cust_equip_id)"></i>
                                <i class="fas fa-trash text-danger pointer float-left" title="Perminately Delete" v-b-tooltip.hover @click="destroy('equipment', item.cust_equip_id)"></i>
                                {{item.name}}
                                <span class="float-right text-muted">Deleted: {{item.deleted_at}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-2" v-if="deleted['contacts'] && deleted['contacts'].length > 0">
                        <h5 class="text-center">Deleted Contacts</h5>
                        <ul class="list-group">
                            <li class="list-group-item text-center" v-for="item in deleted['contacts']" :key="item.cont_id">
                                <i class="fas fa-trash-restore text-success pointer float-left mr-2" title="Restore" v-b-tooltip.hover @click="restore('contacts', item.cont_id)"></i>
                                <i class="fas fa-trash text-danger pointer float-left" title="Perminately Delete" v-b-tooltip.hover @click="destroy('contacts', item.cont_id)"></i>
                                {{item.name}}
                                <span class="float-right text-muted">Deleted: {{item.deleted_at}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-2" v-if="deleted['notes'] && deleted['notes'].length > 0">
                        <h5 class="text-center">Deleted Notes</h5>
                        <ul class="list-group">
                            <li class="list-group-item text-center" v-for="item in deleted['notes']" :key="item.note_id">
                                <i class="fas fa-trash-restore text-success pointer float-left mr-2" title="Restore" v-b-tooltip.hover @click="restore('notes', item.note_id)"></i>
                                <i class="fas fa-trash text-danger pointer float-left" title="Perminately Delete" v-b-tooltip.hover @click="destroy('notes', item.note_id)"></i>
                                {{item.subject}}
                                <span class="float-right text-muted">Deleted: {{item.deleted_at}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-2" v-if="deleted['files'] && deleted['files'].length > 0">
                        <h5 class="text-center">Deleted Files</h5>
                        <ul class="list-group">
                            <li class="list-group-item text-center" v-for="item in deleted['files']" :key="item.cust_file_id">
                                <i class="fas fa-trash-restore text-success pointer float-left mr-2" title="Restore" v-b-tooltip.hover @click="restore('files', item.cust_file_id)"></i>
                                <i class="fas fa-trash text-danger pointer float-left" title="Perminately Delete" v-b-tooltip.hover @click="destroy('files', item.cust_file_id)"></i>
                                {{item.name}}
                                <span class="float-right text-muted">Deleted: {{item.deleted_at}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <link-customer
                        v-show="!linked && !is_parent"
                        :cust_id="cust_id"
                        @completed="closeModal"
                    ></link-customer>
                    <unlink-customer
                        v-show="linked && !is_parent"
                        :cust_id="cust_id"
                        @completed="closeModal"
                    ></unlink-customer>
                    <deactivate-customer
                        v-show="!linked && !is_parent"
                        :cust_id="cust_id"
                    ></deactivate-customer>
                </div>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import LinkCustomer       from './Manage/linkCustomer.vue';
    import UnlinkCustomer     from './Manage/unlinkCustomer.vue';
    import DeactivateCustomer from './Manage/deactivateCustomer.vue';

    export default {
        components: { LinkCustomer, UnlinkCustomer, DeactivateCustomer },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            can_deactivate: {
                type:    Boolean,
                default: false,
            },
            linked: {
                type:    Boolean,
                default: false,
            },
            is_parent: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                loading:       false,
                deleted:       [],
                showLinkModal: false,
            }
        },
        methods: {
            /**
             * Close the Manage Customer Modal
             */
            closeModal()
            {
                this.$refs['manage-customer-modal'].hide();
            },
            /**
             * Get all items that have been soft deleted for the customer
             */
            getDeletedItems()
            {
                this.loading = true;
                axios.get(this.route('customers.get-deleted', this.cust_id))
                    .then(res => {
                        this.deleted = res.data;
                        this.loading = false;

                        console.log(res.data);
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            /**
             * Restore an item that was soft deleted
             */
            restore(type, item)
            {
                console.log('restore');
                this.loading = true;
                this.$inertia.get(this.route('customers.'+type+'.restore', item));
            },
            /**
             * Permanently delete a soft deleted item
             */
            destroy(type, item)
            {
                this.$bvModal.msgBoxConfirm('This action cannot be undone', {
                    title:       'Are You Sure?',
                    size:        'md',
                    okVariant:   'danger',
                    okTitle:     'Yes',
                    cancelTitle: 'No',
                    centered:     true,
                }).then(res => {
                    if(res)
                    {
                        this.loading = true;
                        this.$inertia.delete(this.route('customers.'+type+'.force-delete', item), {
                            onFinish: () => {
                                this.$refs['manage-customer-modal'].hide();
                                this.loading = false;
                            }
                        });
                    }
                });
            },
        },
    }
</script>

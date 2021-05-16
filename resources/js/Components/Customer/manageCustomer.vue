<template>
    <b-button class="mt-1" pill variant="danger" size="sm" v-b-modal.manage-customer-modal @click="getDeletedItems()">
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
                <div class="text-center mt-2">
                    <b-button variant="warning" v-if="!linked && !is_parent" @click="$refs['quick-search'].open()">Link to Parent Customer</b-button>
                    <b-button variant="warning" v-if="linked && !is_parent" @click="breakLink">Break Link to Parent</b-button>
                    <b-button variant="danger" v-if="!linked && !is_parent" @click="deactivate">Deactivate Customer</b-button>
                </div>
            </b-overlay>
        </b-modal>
        <quick-search
            ref="quick-search"
            modal-title="Select Parent Customer"
            @selected-customer="linkCustomer"
        ></quick-search>
    </b-button>
</template>

<script>
    import quickSearch from './quickSearch.vue';

    export default {
        components: { quickSearch },
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
                loading:       true,
                deleted:       [],
                showLinkModal: false,
            }
        },
        methods: {
            //  Get all items that have been soft deleted from the customer
            getDeletedItems()
            {
                this.loading = true;
                axios.get(this.route('customers.get-deleted', this.cust_id))
                    .then(res => {
                        this.deleted = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            //  Link this customer to another customer site
            linkCustomer(cust)
            {
                //  A customer cannot be its own parent
                if(cust.cust_id === this.cust_id)
                {
                    this.$bvModal.msgBoxOk('Customer Cannot Be Its Own Parent', {
                        title: 'ERROR',
                        size: 'sm',
                        buttonSize: 'sm',
                        okVariant: 'danger',
                        headerClass: 'p-2 border-bottom-0',
                        footerClass: 'p-2 border-top-0',
                        centered: true
                    });
                }
                else
                {
                    this.loading = true;
                    this.$inertia.post(route('customers.link-customer'), {cust_id: this.cust_id, parent_id: cust.cust_id},
                        { onFinish: () => { this.$refs['manage-customer-modal'].hide(); } });
                }
            },
            //  Make customer solo site and not linked to a parent site
            breakLink()
            {
                this.$bvModal.msgBoxConfirm('Breaking the link will remove any shared data', {
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
                        this.$inertia.get(route('customers.break-link', this.cust_id),
                            { onFinish: () => { this.$refs['manage-customer-modal'].hide(); } });
                    }
                });
            },
            //  Deactivate the customer so they no longer show in customer list
            deactivate()
            {
                this.$bvModal.msgBoxConfirm('Deactivating Customer will make it inaccessable', {
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
                        this.$inertia.delete(route('customers.destroy', this.cust_id));
                    }
                });
            },
            //  Restore an item that has been deleted
            restore(type, item)
            {
                this.loading = true;
                this.$inertia.get(this.route('customers.'+type+'.restore', item));
            },
            //  Permanently delete an item that was deleted
            destroy(type, item)
            {
                this.$bvModal.msgBoxConfirm('This action cannot be undone', {
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
                        this.$inertia.delete(this.route('customers.'+type+'.force-delete', item), {
                            onFinish: () => { this.$refs['manage-customer-modal'].hide(); }
                        });
                    }
                });
            },
        },
    }
</script>

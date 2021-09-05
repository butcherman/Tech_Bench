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
                <div>deleted stuff</div>
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


                    <!-- <b-button variant="warning" v-if="linked && !is_parent" @click="breakLink">Break Link to Parent</b-button> -->
                    <b-button variant="danger" v-if="!linked && !is_parent" @click="deactivate">Deactivate Customer</b-button>
                </div>
            </b-overlay>
        </b-modal>

    </b-button>
</template>

<script>
    import LinkCustomer from './Manage/linkCustomer.vue';
    import UnlinkCustomer from './Manage/unlinkCustomer.vue';

    export default {
        components: { LinkCustomer, UnlinkCustomer },
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
            closeModal()
            {
                console.log('triggered');
                this.$refs['manage-customer-modal'].hide();
            },
            //  Get all items that have been soft deleted from the customer
            getDeletedItems()
            {
                // this.loading = true;
                // axios.get(this.route('customers.get-deleted', this.cust_id))
                //     .then(res => {
                //         this.deleted = res.data;
                //         this.loading = false;
                //     }).catch(error => this.eventHub.$emit('axiosError', error));
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
                        // this.$inertia.get(route('customers.break-link', this.cust_id),
                        //     { onFinish: () => { this.$refs['manage-customer-modal'].hide(); } });
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
                        console.log('deactivate');
                        // this.$inertia.delete(route('customers.destroy', this.cust_id));
                    }
                });
            },
            //  Restore an item that has been deleted
            restore(type, item)
            {
                this.loading = true;
                // this.$inertia.get(this.route('customers.'+type+'.restore', item));
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
                        // this.$inertia.delete(this.route('customers.'+type+'.force-delete', item), {
                        //     onFinish: () => { this.$refs['manage-customer-modal'].hide(); }
                        // });
                    }
                });
            },
        },
    }
</script>

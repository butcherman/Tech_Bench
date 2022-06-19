<template>
    <b-button
        v-if="customerStore.userPerm.manage"
        size="sm"
        class="mt-1"
        variant="danger"
        title="Manage Customer"
        pill
        block
        v-b-tooltip.hover
        v-b-modal.manage-customer-modal
        @click="getDeletedItems"
    >
        <i class="fas fa-tasks" />
        Manage
        <b-modal
            id="manage-customer-modal"
            ref="manage-customer-modal"
            title="Manage Customer"
            hide-footer
        >
            <b-overlay :show="loading" no-center>
                <template #overlay>
                    <atom-loader text="Loading Data..." />
                </template>
                <div v-for="(group, index) in deleted" :key="index">
                    <div v-if="group.length">
                        <h5 class="text-center">Deleted {{index}}</h5>
                        <ul class="list-group">
                            <li
                                v-for="item in group"
                                class="list-group-item text-center"
                                :key="item.item_id"
                            >
                                <i
                                    class="fas fa-trash-restore text-success pointer float-left mr-2"
                                    title="Restore"
                                    v-b-tooltip.hover
                                    @click="restoreDeletedItem(index, item.item_id)"
                                />
                                <i
                                    class="fas fa-trash text-danger pointer float-left"
                                    title="Perminately Delete"
                                    v-b-tooltip.hover
                                    @click="destroyDeletedItem(index, item.item_id)"
                                />
                                {{item.item_name}}
                                <span
                                    class="float-right text-muted"
                                >
                                    Deleted: {{item.item_deleted}}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <link-customer
                        v-show="canLink"
                        @loading="loading = true"
                        @completed="closeModal"
                    />
                    <unlink-customer
                        v-show="isLinked"
                        @loading="loading = true"
                        @completed="closeModal"
                    ></unlink-customer>
                    <deactivate-customer
                        v-show="canLink"
                        @loading="loading = true"
                    ></deactivate-customer>
                </div>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                loading: false,
                deleted: [],
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            canLink()
            {
                if(this.customerStore.custDetails.parent_id ||
                    this.customerStore.custDetails.child_count > 0)
                {
                    return false;
                }

                return true;
            },
            isLinked()
            {
                if(this.customerStore.custDetails.parent_id)
                {
                    return true;
                }

                return false;
            }
        },
        methods: {
            /**
             * Get all items that have been soft deleted for the customer
             */
            getDeletedItems()
            {
                this.loading = true;
                axios.get(route('customers.get-deleted', this.customerStore.custDetails.cust_id))
                    .then(res => {
                        this.deleted = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            restoreDeletedItem(group, itemId)
            {
                this.loading = true;
                this.$inertia.get(this.route(`customers.${group}.restore`, itemId), {
                    only     : ['equipment', 'flash', 'errors'],
                    onSuccess: ()      => this.$refs['manage-customer-modal'].hide(),
                    onError  : (error) => this.eventHub.$emit('validationError', error),
                    onFinish : ()      => this.loading = false,
                });
            },
            destroyDeletedItem(group, itemId)
            {
                this.$bvModal.msgBoxConfirm('This action cannot be undone', {
                    title      : 'Are You Sure?',
                    size       : 'md',
                    okVariant  : 'danger',
                    okTitle    : 'Yes',
                    cancelTitle: 'No',
                    centered   : true,
                }).then(res => {
                    if(res)
                    {
                        this.loading = true;
                        this.$inertia.delete(this.route(`customers.${group}.force-delete`, itemId), {
                            only     : ['equipment', 'flash', 'errors'],
                            onSuccess: ()      => this.$refs['manage-customer-modal'].hide(),
                            onError  : (error) => this.eventHub.$emit('validationError', error),
                            onFinish : ()      => this.loading = false,
                        });
                    }
                });
            },
            closeModal()
            {
                this.loading = false;
                this.$refs['manage-customer-modal'].hide();
            }
        },
    }
</script>

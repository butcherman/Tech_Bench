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
                <div v-for="(item, index) in deleted" :key="index">
                    <div v-if="item.length">
                        <h5 class="text-center">Deleted {{index}}</h5>
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
        props: {
            //
        },
        data() {
            return {
                loading: false,
                deleted: [],
            }
        },
        created() {
            //
        },
        mounted() {
            //
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
        watch: {
            //
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
            closeModal()
            {
                this.loading = false;
                this.$refs['manage-customer-modal'].hide();
            }
        },
    }
</script>

<template>
    <b-button
        class="m-2"
        variant="danger"
        @click="deactivate"
    >
        Deactivate Customer
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            deactivate()
            {
                this.$bvModal.msgBoxConfirm('Deactivating Customer will make it inaccessable', {
                    title      : 'Are You Sure?',
                    size       : 'md',
                    okVariant  : 'danger',
                    okTitle    : 'Yes',
                    cancelTitle: 'No',
                    centered   : true,
                }).then(res => {
                    if(res)
                    {
                        this.$emit('loading');
                        this.$inertia.delete(route('customers.destroy', this.customerStore.custDetails.cust_id), {
                            onError : (err) => this.eventHub.$emit('validation-error', err),
                        });
                    }
                });
            },
        },
    }
</script>

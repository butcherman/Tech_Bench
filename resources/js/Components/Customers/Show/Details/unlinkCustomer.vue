<template>
    <b-button
        class="m-2"
        variant="warning"
        @click="breakLink"
    >
        Break Link to Parent
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
            breakLink()
            {
                this.$bvModal.msgBoxConfirm('Breaking the link will remove any shared data', {
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
                        this.$inertia.post(route('customers.link-customer'), {
                            cust_id  : this.customerStore.custDetails.cust_id,
                            parent_id: null,
                            add      : false,
                        }, {
                            only    : ['details', 'flash', 'errors'],
                            onFinish: ()    => this.$emit('completed'),
                            onError : (err) => this.eventHub.$emit('validation-error', err),
                        });
                    }
                });
            }
        },
    }
</script>

<template>
    <b-button
        class="m-2"
        variant="warning"
        @click="$refs['quick-search'].open()"
    >
        Link to Parent Customer
        <quick-search
            ref="quick-search"
            modal-title="Select Parent Customer"
            @selected-customer="linkCustomer"
        ></quick-search>
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';
    import quickSearch          from '../../quickSearch.vue';

    export default {
        components: { quickSearch },
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            linkCustomer(cust)
            {
                //  A customer cannot be its own parent
                if(cust.cust_id === this.customerStore.custDetails.cust_id)
                {
                    this.$bvModal.msgBoxOk('Customer Cannot Be Its Own Parent', {
                        title:       'ERROR',
                        size:        'sm',
                        buttonSize:  'sm',
                        okVariant:   'danger',
                        headerClass: 'p-2 border-bottom-0',
                        footerClass: 'p-2 border-top-0',
                        centered:     true
                    });
                }
                else
                {
                    this.$bvModal.msgBoxConfirm(`You are about to link this customer to ${cust.name}`, {
                        title      : 'Are You Sure?',
                        size       : 'sm',
                        buttonSize : 'sm',
                        okVariant  : 'danger',
                        headerClass: 'p-2 border-bottom-0',
                        footerClass: 'p-2 border-top-0',
                        centered   : true
                    }).then(res => {
                        if(res)
                        {
                            this.$emit('loading');
                            this.$inertia.post(route('customers.link-customer'), {
                                cust_id  : this.customerStore.custDetails.cust_id,
                                parent_id: cust.cust_id,
                                add      : true,
                            }, {
                                only    : ['details', 'flash', 'errors'],
                                onFinish: ()    => this.$emit('completed'),
                                onError : (err) => this.eventHub.$emit('validation-error', err),
                            });
                        }
                    });
                }
            }
        },
    }
</script>

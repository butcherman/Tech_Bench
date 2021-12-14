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
    import quickSearch from './../quickSearch.vue';

    export default {
        components: { quickSearch },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        methods: {
            linkCustomer(cust)
            {
                //  A customer cannot be its own parent
                if(cust.cust_id === this.cust_id)
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
                    this.$bvModal.msgBoxConfirm('You are about to link this customer to '+cust.name, {
                        title:       'Are You Sure?',
                        size:        'sm',
                        buttonSize:  'sm',
                        okVariant:   'danger',
                        headerClass: 'p-2 border-bottom-0',
                        footerClass: 'p-2 border-top-0',
                        centered:     true
                    }).then(res => {
                        if(res)
                        {
                            this.$emit('loading');
                            this.$inertia.post(route('customers.link-customer'), {
                                cust_id:   this.cust_id,
                                parent_id: cust.cust_id,
                                add:       true,
                            }, {
                                onFinish: () => {
                                    this.$emit('completed');
                                }
                            });
                        }
                    });
                }
            },
        },
    }
</script>

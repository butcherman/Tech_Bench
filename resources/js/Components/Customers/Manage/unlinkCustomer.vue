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
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        methods: {
            breakLink()
            {
                this.$bvModal.msgBoxConfirm('Breaking the link will remove any shared data', {
                    title:       'Are You Sure?',
                    size:        'md',
                    okVariant:   'danger',
                    okTitle:     'Yes',
                    cancelTitle: 'No',
                    centered:     true,
                }).then(res => {
                    if(res)
                    {
                        this.$emit('loading');
                        this.loading = true;
                        this.$inertia.post(route('customers.link-customer'), {
                            cust_id:   this.cust_id,
                            parent_id: null,
                            add:       false,
                        }, {
                            onFinish: () => {
                                this.$emit('completed');
                            }
                        });
                    }
                });
            },
        },
    }
</script>

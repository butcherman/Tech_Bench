<template>
    <b-button
        variant="danger"
        @click="deactivate"
    >
        Deactivate Customer
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
        },
    }
</script>

<template>
    <b-button
        variant="danger"
        size="sm"
        pill
        @click="deleteEquip"
    >
        <i class="fas fa-trash-alt" />
        Delete
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        props: {
            equipIndex: {
                type    : Number,
                required: true,
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            equip()
            {
                return this.customerStore.equipment[this.equipIndex];
            }
        },
        methods: {
            deleteEquip()
            {
                this.$bvModal.msgBoxConfirm('All information for this equipment will also be deleted',
                {
                    title          : 'Are you sure?',
                    size           : 'sm',
                    buttonSize     : 'sm',
                    okVariant      : 'danger',
                    okTitle        : 'YES',
                    cancelTitle    : 'NO',
                    footerClass    : 'p-2',
                    hideHeaderClose: false,
                    centered       : true
                }).then(value => {
                    if(value)
                    {
                        this.$inertia.delete(
                            this.route('customers.equipment.destroy', this.equip.cust_equip_id),
                            {
                                only   : ['equipment', 'flash', 'errors'],
                                onError: (error) => this.eventHub.$emit('validation-error', error),
                            });
                    }
                });
            }
        },
    }
</script>

<template>
    <b-button variant="danger" size="sm" pill @click="deleteEquip">
        <i class="fas fa-trash-alt"></i>
        Delete Equipment
    </b-button>
</template>

<script>
    export default {
        props: {
            cust_equip_id: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                //
            }
        },
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            //
            deleteEquip()
            {
                this.$bvModal.msgBoxConfirm('All information for this equipment will also be deleted',
                {
                    title:          'Are you sure?',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                }).then(value => {
                    if(value)
                    {
                        this.loading = true;
                        this.$inertia.delete(this.route('customers.equipment.destroy', this.cust_equip_id), {
                            onFinish: () => {
                                //
                            }
                        })
                    }
                });





            }
        },
    }
</script>

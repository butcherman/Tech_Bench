<template>
    <b-button
        variant="danger"
        size="sm"
        pill
        @click="deleteContact"
    >
        <i class="fas fa-trash-alt" />
    </b-button>
</template>

<script>
    export default {
        props: {
            contId: {
                type    : Number,
                required: true,
            }
        },
        methods: {
            deleteContact()
            {
                this.$bvModal.msgBoxConfirm('All information for this contact will be deleted',
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
                            this.route('customers.contacts.destroy', this.contId),
                            {
                                only   : ['contacts', 'flash', 'errors'],
                                onError: (error) => this.eventHub.$emit('validation-error', error),
                            });
                    }
                });
            }
        },
    }
</script>

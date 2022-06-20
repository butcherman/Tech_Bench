<template>
    <b-button
        variant="danger"
        size="sm"
        pill
        @click="deleteFile"
    >
        <i class="fas fa-trash-alt" />
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        props: {
            fileId: {
                type    : Number,
                required: true,
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            deleteFile()
            {
                this.$bvModal.msgBoxConfirm('Please Verify',
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
                        this.$emit('loading');

                        this.$inertia.delete(
                            this.route('customers.files.destroy', this.fileId),
                            {
                                only     : ['files', 'flash', 'errors'],
                                onError  : (error) => this.eventHub.$emit('validation-error', error),
                                onSuccess: ()      => this.$emit('completed'),
                            });
                    }
                });
            }
        },
    }
</script>

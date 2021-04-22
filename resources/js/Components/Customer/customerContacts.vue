<template>
    <div>
        <div class="card-title">
            Contacts:
            <new-contact-modal :cust_id="cust_id"></new-contact-modal>
        </div>
        <b-overlay :show="loading">
            blah
        </b-overlay>
    </div>
</template>

<script>
    import newContactModal from './Contacts/newContactModal.vue';

    export default {
        components: { newContactModal },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
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
        },
    }
</script>

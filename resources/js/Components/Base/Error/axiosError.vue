<template>
    <b-modal ref="axios-error-modal" size="xl" centered title="ERROR" ok-only header-text-variant="danger" title-class="text-center w-100">
        <h4 class="text-center">Something Bad Happened</h4>
        <p class="text-center">There was a prolem....</p>

    </b-modal>
</template>

<script>
    export default {
        props: {
            //
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
            this.eventHub.$on('axiosError', err => {
                this.$refs['axios-error-modal'].show();
            });
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

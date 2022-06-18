<!--
    Used for calling generic Validation Errors during Inertia Requests

    Props: none
-->

<template>
    <b-modal ref="validation-error-modal" size="xl" centered title="ERROR" ok-only header-text-variant="danger" title-class="text-center w-100">
        <h4 class="text-center">Something Is Not Quite Right</h4>
        <div v-for="(message, index) in message" :key="index">
            <p class="text-center text-danger">{{message}}</p>
        </div>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                message: null,
            }
        },
        mounted() {
            this.eventHub.$on('validationError', err => {
                this.message = err;
                this.$refs['validation-error-modal'].show();
            });
        },
    }
</script>

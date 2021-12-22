<template>
    <b-modal ref="axios-error-modal" size="xl" centered title="ERROR" ok-only header-text-variant="danger" title-class="text-center w-100">
        <h4 class="text-center">{{header}}</h4>
        <p class="text-center">{{message}}</p>
        <div class="text-center mt-2" v-if="details">
            <b-button pill size="sm" variant="info" @click="showDetails = !showDetails">{{showHide}} Technical Details</b-button>
        </div>
        <transition name="fade">
            <div v-if="showDetails" class="message-text mt-5 text-center">
                <code v-html="details"></code>
            </div>
        </transition>
    </b-modal>
</template>

<script>
    export default {
        props: {
            //
        },
        data() {
            return {
                header:      null,
                message:     null,
                details:     null,
                showDetails: false,
            }
        },
        created() {
            //
        },
        mounted() {
            this.eventHub.$on('axiosError', err => {
                console.log(err.response);
                this.assignError(err);
                this.$refs['axios-error-modal'].show();
            });
        },
        computed: {
            showHide()
            {
                return this.showDetails ? 'Hide' : 'Show';
            }
        },
        watch: {
            //
        },
        methods: {
            assignError(err)
            {
                this.header  = 'Something Bad Happened';
                this.message = 'Server responded with Error Code '+err.response.status+' - '+err.response.statusText;
                if(err.response.data.message)
                {
                    this.details = err.response.data.message
                }
            },
        },
    }
</script>

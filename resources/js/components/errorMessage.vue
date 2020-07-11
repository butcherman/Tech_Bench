<template>
    <b-modal ref="axios-error-modal" size="xl" centered title="ERROR" ok-only header-text-variant="danger" title-class="text-center w-100">
        <h4 class="text-center">{{header}}</h4>
        <p class="text-center">{{message}}</p>
        <div class="text-center mt-2" v-if="details">
            <b-button pill size="sm" variant="info" @click="showDetails = !showDetails">{{showHide}} Technical Details</b-button>
        </div>
        <transition name="fade">
            <div v-if="showDetails" class="message-text mt-5">
                <code v-html="details"></code>
            </div>
        </transition>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                header:  null,
                message: null,
                details: null,
                showDetails: false,
            }
        },
        mounted() {
             this.eventHub.$on('axiosError', err => {
                 console.log(err);
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
        methods: {
            assignError(err)
            {
                switch(err.response.status)
                {
                    case 403:
                        this.error403(err);
                        break;
                    case 422:
                        this.error422(err);
                        break;
                    case 428:
                        this.error428(err);
                        break;
                    default:
                        this.error500(err);
                }
            },
            error500(err)
            {
                this.header  = 'Something Bad Happened';
                this.message = 'Server responded with Error Code '+err.response.status+' - '+err.response.statusText;
                this.details = err.response.data
            },
            error403(err)
            {
                this.header  = 'You are not authorized to make this request';
                this.message = 'Server responded with Error Code '+err.response.status+' - '+err.response.statusText;
            },
            error422(err)
            {
                this.header  = 'There was a problem with the information you submitted';
                this.message = 'Please check the Technical Details for additional information';

                var details = '<ul class="list-group">';
                if(err.response.data)
                {
                    var errObj = err.response.data.errors;
                }
                else
                {
                    var errObj = null;
                }

                Object.keys(errObj).forEach(function(key)
                {
                    details = details+'<li class="list-group-item text-center">'+key+' - '+errObj[key]+'</li>'
                });

                this.details = details+'</ul>';
            },
            error428(err)
            {
                this.header  = 'There was a problem with the information you submitted';
                this.message = err.response.data.message;
                // this.details = err.response.message;
            }
        }
    }
</script>

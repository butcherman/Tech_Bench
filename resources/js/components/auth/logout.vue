<template>
        <i :class="iconClass" title="logout" v-b-tooltip.hover @click="logout"></i>
</template>

<script>
    export default {
        data() {
            return {
                processing: false,
            }
        },
        computed: {
             iconClass()
             {
                 return this.processing ? 'spinner-grow text-success' : 'fas fa-sign-out-alt pointer';
             }
        },
        methods: {
            logout()
            {
                this.processing = true;
                axios.post(this.route('logout'), this.form)
                    .then(res => {
                        location.href = this.route('logout_complete');
                    }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
            }
        }
    }
</script>

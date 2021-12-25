<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Application Backups</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body text-center">
                        <b-button variant="info" block @click="runBackup" :disabled="runningBackup">
                            <span class="spinner-border spinner-border-sm text-danger" v-show="runningBackup"></span>
                            {{buttonText}}
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            //
        },
        data() {
            return {
                runningBackup: false,
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
            buttonText()
            {
                return this.runningBackup ? 'Backing Up Now' : 'Run Backup';
            }
        },
        watch: {
             //
        },
        methods: {
            //
            runBackup()
            {
                console.log('run backup');
                this.runningBackup = true;

                axios.get(route('admin.backups.show', 'run'))
                    .then(res => {
                        console.log(res);
                        this.runningBackup = false;
                    }).catch(error => {
                        this.eventHub.$emit('axiosError', error)
                        this.runningBackup = false;
                    });
            }
        }
    }
</script>

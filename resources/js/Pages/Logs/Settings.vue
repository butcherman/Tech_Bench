<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Log Settings</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.days" rules="required" type="number" label="Days to Keep Logs" name="days"></text-input>
                                    <dropdown-input v-model="form.level" rules="required" :options="types" label="Logging Level"></dropdown-input>
                                    <submit-button button_text="Update Log Settings" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
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
            /**
             * Current log level
             */
            log_level: {
                type:     String,
                required: true,
            },
            /**
             * Number of days to keep logs
             */
            days: {
                type:     Number|String,
                required: true,
            },
            /**
             * Array of all possible log levels
             */
            types: {
                type:     Array,
                required: true,
            },
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    days: this.days,
                    level: this.log_level,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('admin.logs.set-settings'), {
                    onFinish: ()=> {
                        this.submitted = false;
                    }
                });
            }
        }
    }
</script>

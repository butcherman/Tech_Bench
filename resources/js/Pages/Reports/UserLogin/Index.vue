<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">User Login Report</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Select Options:</div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Generating Report..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 grid-margin">
                                            <date-picker v-model="form.start_date" rules="required" label="Start Date"></date-picker>
                                        </div>
                                        <div class="col-md-6">
                                            <date-picker v-model="form.stop_date" rules="required" label="Stop Date"></date-picker>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-4 grid-margin">
                                            <label>Available Users</label>
                                            <b-form-select multiple :options="available_users" v-model="add_users" :select-size="10" value-field="username" text-field="full_name"></b-form-select>
                                        </div>
                                        <div class="col-md-1 d-md-flex align-items-center">
                                            <div class="text-center">
                                                <b-button variant="info" block @click="add_item"><i class="fas fa-angle-double-right"></i></b-button>
                                                <b-button variant="info" block @click="remove_item"><i class="fas fa-angle-double-left"></i></b-button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 grid-margin">
                                            <label>Selected Users</label>
                                            <b-form-select multiple :options="form.selected_list" v-model="rem_users" :select-size="10" value-field="username" text-field="full_name"></b-form-select>
                                        </div>
                                    </div>
                                    <submit-button button_text="Run Report" :submitted="submitted" class="mt-3" />
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
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Array of objects from /app/Models/User
             */
            user_list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                available_users: this.user_list,
                add_users:       [],
                rem_users:       [],
                submitted:       false,
                form: this.$inertia.form({
                    start_date:    new Date(Date.now() - 7 * 24 * 60 * 60 * 1000),
                    stop_date:     new Date(),
                    selected_list: [],
                }),
            }
        },
        methods: {
            submitForm()
            {
                if(this.form.selected_list.length == 0)
                {
                    this.$bvModal.msgBoxOk('You must add at least one User', {
                        title: 'Error:',
                        size: 'sm',
                        buttonSize: 'sm',
                        centered: true
                    });
                }
                else
                {
                    this.submitted = true;
                    this.form.put(route('reports.user-login-report.update', 'details'));
                }
            },
            add_item()
            {
                var form = this.form;
                var list = this.available_users;

                this.add_users.forEach(function(elem, index)
                {
                    for(var i = 0; i < list.length; i++)
                    {
                        if(list[i].username === elem)
                        {
                            form.selected_list.push(list[i]);
                            list.splice(i, 1);
                        }
                    }
                });

                this.add_users = [];
            },
            remove_item()
            {
                var form = this.form;
                var list = this.available_users;

                this.rem_users.forEach(function(elem, index)
                {
                    for(var i = 0; i < form.selected_list.length; i++)
                    {
                        if(form.selected_list[i].username === elem)
                        {
                            list.push(form.selected_list[i]);
                            form.selected_list.splice(i, 1);
                        }
                    }
                });

                this.rem_users = [];
            }
        }
    }
</script>

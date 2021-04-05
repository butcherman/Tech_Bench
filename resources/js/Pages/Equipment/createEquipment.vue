<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Equipment</h4>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>

                                <dropdown-input
                                    v-model="form.cat_id"
                                    label="Select Category"
                                    name="category"
                                    text-field="name"
                                    value-field="cat_id"
                                    placeholder="Select A Category This Equipment Belongs To"
                                    rules="required"
                                    :options="categories">
                                </dropdown-input>
                                <text-input v-model="form.name" label="Equipment Name" rules="required|no-special" name="name" placeholder="Enter A Unique Name for the Equipment"></text-input>
                                <fieldset>
                                    <label>Customer Information to Gather</label>
                                </fieldset>

                                <!-- TODO - Input customer information to gather -->

                                <submit-button button_text="Create Equipment" :submitted="submitted"></submit-button>
                            </b-form>
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
            //
            categories: {
                type: Array,
                required: true,
            }
        },
        data() {
            return {
                //
                submitted: false,
                form: {
                    cat_id: '',
                    name: '',
                }
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
            submitForm()
            {
                console.log(this.form);
            }
        }
    }
</script>

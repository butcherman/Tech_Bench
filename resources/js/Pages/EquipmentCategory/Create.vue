<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Create New Equipment Category</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.name" rules="required" label="New Category Name" name="name"></text-input>
                                    <submit-button button_text="Create New Category" :submitted="submitted" class="mt-3" />
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
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    name: null,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('equipment-categories.store'), {
                    onFinish: ()=> {
                        this.submitted = false;
                    }
                });
            }
        }
    }
</script>

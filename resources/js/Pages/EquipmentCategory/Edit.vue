<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Edit Category</h4>
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
                                    <text-input v-model="form.name" rules="required" label="Category Name" name="name"></text-input>
                                    <submit-button button_text="Update Category" :submitted="submitted" class="mt-3" />
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
            category: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    name: this.category.name,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('equipment-categories.update', this.category.cat_id), {
                    onFinish: ()=> {
                        this.submitted = false;
                    }
                });
            }
        }
    }
</script>

<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Equipment Categories</h4>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                <text-input v-model="form.name" label="Category Name" rules="required|no-special" name="name" placeholder="Enter A Unique Name for the Category"></text-input>
                                <submit-button :button_text="button" :submitted="submitted"></submit-button>
                            </b-form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gris-margin justify-content-center" v-if="cat">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <b-button variant="danger" block @click="deleteCategory">Delete Category</b-button>
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
            cat: {
                type:     Object,
                required: false,
            }
        },
        data() {
            return {
                submitted: false,
                form: {
                    name: this.cat ? this.cat.name : '',
                }
            }
        },
        computed: {
            button()
            {
                return this.cat ? 'Update Category' : 'Create Category';
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                if(this.cat)
                {
                    this.$inertia.put(this.route('admin.equipment.categories.update', this.cat.cat_id), this.form);
                }
                else
                {
                    this.$inertia.post(this.route('admin.equipment.categories.store'), this.form);
                }
            },
            deleteCategory()
            {
                this.$bvModal.msgBoxConfirm('Are you sure you want to delete this Category?', {
                    title:          'This action canot be undone',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                }).then(value => {
                    if(value)
                    {
                        this.submitted = true;
                        this.$inertia.delete(this.route('admin.equipment.categories.destroy', this.cat.cat_id));
                    }
                });
            }
        }
    }
</script>

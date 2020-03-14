<template>
    <div>
        <b-list-group>
            <b-list-group-item v-for="cat in categories" :key="cat.cat_id">
                <b-button variant="primary" block @click="editCategory(cat)">{{cat.name}}</b-button>
            </b-list-group-item>
            <b-list-group-item>
                <b-button variant="warning" block @click="newCategory">Create Category</b-button>
            </b-list-group-item>
        </b-list-group>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </b-modal>
        <b-modal :title="modalTitle" id="categories-modal" ref="categoriesModal" hide-footer>
            <b-form @submit="submitForm" ref="categoryForm" novalidate :validated="validated">
                <b-form-group label="Name:" label-for="name">
                    <b-form-input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        placeholder="Example - Cisco"
                    ></b-form-input>
                    <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                </b-form-group>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <b-button type="submit" block variant="primary" class="pad-top" :disabled="button.disable">
                            <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                            {{button.text}}
                        </b-button>
                    </div>
                </div>
                <div class="row justify-content-center" v-show="form.edit">
                    <div class="col-md-6">
                        <b-button block variant="danger" class="mt-3" @click="deleteCategory">
                            Delete Category
                        </b-button>
                    </div>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'categories',
    ],
    data() {
        return {
            validated: false,
            modalTitle: 'Create New Category',
            form: {
                name: '',
                edit: false,
            },
            button: {
                disable: false,
                text: 'Add Category',
            }
        }
    },
    created()
    {
        //
    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            if(this.$refs.categoryForm.checkValidity() === false)
            {
                //  TODO - validate category name is valid and does not have any illegal characters
                this.validated = true;
            }
            else
            {
                this.button.text = 'Processing...';
                this.button.disable = true;
                if(this.form.edit)
                {
                     axios.put(this.route('admin.categories.update', this.form.edit), this.form)
                        .then(res => {
                            location.reload();
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
                else
                {
                    axios.post(this.route('admin.categories.store'), this.form)
                        .then(res => {
                            location.reload();
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            }
        },
        editCategory(data)
        {
               this.button.text = 'Update Category';
                this.form.name = data.name;
                this.form.edit = data.cat_id;
                this.modalTitle = 'Edit '+data.name;
                this.$refs['categoriesModal'].show();
        },
        newCategory()
        {
            this.$refs['categoriesModal'].show();
        },
        deleteCategory()
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this category. ', {
                title: 'PLEASE CONFIRM - THIS CANNOT BE UNDONE',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('admin.categories.destroy', this.form.edit))
                        .then(res => {
                            this.$refs['loading-modal'].hide();
                            this.$bvModal.msgBoxOk(res.data.reason)
                                .then(value => {
                                    location.reload();
                                });
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            })
        },
        reset()
        {
            this.validated      =  false;
            this.modalTitle     =  'Create New Category';
            this.form.name      =  '';
            this.form.edit      =  false;
            this.button.disable =  false;
            this.button.text    =  'Add Category';
        }
    }
}
</script>

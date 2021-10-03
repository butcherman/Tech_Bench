<template>
    <div>
        <h4>Customer File Types</h4>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">When uploading a file for a customer, these are the available file types</div>
                        <b-overlay :show="loading">
                            <template #overlay>
                                <atom-loader></atom-loader>
                            </template>
                            <b-list-group>
                                <b-list-group-item v-for="type in file_types" :key="type.file_type_id">
                                    {{type.description}}
                                    <i class="far fa-trash-alt float-right text-danger ml-2 pointer" title="Delete" v-b-tooltip.hover @click="deleteType(type)"></i>
                                    <i class="fas fa-pencil-alt float-right ml-2 pointer" title="Edit" v-b-tooltip.hover @click="editType(type)"></i>
                                </b-list-group-item>
                            </b-list-group>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Add New File Type
                        </div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-form @submit.prevent="handleSubmit(submitNewForm)" novalidate>
                                <text-input label="Description" name="description" v-model="newForm.description" rules="required"></text-input>
                                <submit-button button_text="Create File Type" :submitted="submittedNew"></submit-button>
                            </b-form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="edit-type-modal" title="Edit File Type" hide-footer>
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitEditForm)" novalidate>
                        <text-input label="Description" name="description" v-model="editForm.description" rules="required"></text-input>
                        <submit-button button_text="Update File Type" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            file_types: {
                type: Array,
                required: true,
            }
        },
        data() {
            return {
                loading:      false,
                submitted:    false,
                submittedNew: false,
                editForm: {
                    description:  null,
                    file_type_id: null,
                },
                newForm: {
                    description: null,
                }
            }
        },
        methods: {
            editType(type)
            {
                this.editForm.file_type_id = type.file_type_id;
                this.editForm.description  = type.description;
                this.$refs['edit-type-modal'].show();
            },
            deleteType(type)
            {
                this.$bvModal.msgBoxConfirm('Please Confirm',
                    {
                        title:          'Are you sure?',
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
                            this.loading = true;
                            this.$inertia.delete(route('admin.cust.file-types.destroy', type.file_type_id), {
                                onFinish: () =>
                                {
                                    this.loading = false;
                                }
                            });
                        }
                    });
            },
            submitEditForm()
            {
                this.loading = true;
                this.submitted = true;

                this.$inertia.put(route('admin.cust.file-types.update', this.editForm.file_type_id), this.editForm, {
                    onFinish: () => {
                        this.submitted = false;
                        this.loading   = false;
                        this.editForm  = {
                            description: null,
                            file_type_id: null,
                        }

                        this.$refs['edit-type-modal'].hide();
                    }
                })
            },
            submitNewForm()
            {
                this.loading      = true;
                this.submittedNew = true;
                this.$inertia.post(route('admin.cust.file-types.store'), this.newForm, {
                    onFinish: () => {
                        this.newForm.description = null;
                        this.loading             = false;
                        this.submittedNew        = false;
                    }
                });
            }
        }
    }
</script>

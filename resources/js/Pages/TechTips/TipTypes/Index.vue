<template>
    <div>
        <h4>Tech Tip Types</h4>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">When a new Tech Tip is created, these are the types that can be assigned to them</div>
                        <b-overlay :show="loading">
                            <template #overlay>
                                <atom-loader></atom-loader>
                            </template>
                            <b-list-group>
                                <b-list-group-item v-for="type in types" :key="type.tip_type_id">
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
                            Add New Tech Tip Type
                        </div>
                        <ValidationObserver v-slot="{handleSubmit}" ref="newValidator">
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
                        <submit-button button_text="Update Tech Tip Type" :submitted="submitted"></submit-button>
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
            types: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                loading:      false,
                submitted:    false,
                submittedNew: false,
                newForm: this.$inertia.form({
                    description: null,
                }),
                editForm: this.$inertia.form({
                    description: null,
                    tip_type_id: null,
                }),
            }
        },
        methods: {
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
                            this.$inertia.delete(route('admin.tips.tip-types.destroy', type.tip_type_id), {
                                onFinish: () =>
                                {
                                    this.loading = false;
                                }
                            });
                        }
                    });
            },
            editType(type)
            {
                this.editForm.description = type.description;
                this.editForm.tip_type_id = type.tip_type_id;
                this.$refs['edit-type-modal'].show();
            },
            submitNewForm()
            {
                this.submittedNew = true;
                this.loading      = true;

                this.newForm.post(route('admin.tips.tip-types.store'), {
                    onSuccess: () => {
                        this.newForm.reset();
                        this.$refs['newValidator'].reset();

                        this.submittedNew = false;
                        this.loading      = false;
                    }
                });
            },
            submitEditForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.editForm.put(route('admin.tips.tip-types.update', this.editForm.tip_type_id), {
                    onSuccess: () => {
                        this.submitted = false;
                        this.loading   = false;
                        this.$refs['edit-type-modal'].hide();
                    }
                });
            }
        }
    }
</script>

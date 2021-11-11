<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Equipment Data Types</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">When equipment is assigned to a customer, the following Data Types are available to gather information for that equipment</p>
                        <p class="text-center">Data Types that are in use cannot be deleted until they have been removed from all Equipment Types</p>
                        <b-list-group>
                            <b-list-group-item v-for="field in data_list" :key="field.type_id" class="d-flex justify-content-between align-items-center">
                                {{field.name}}
                                <span>
                                    <i class="fas fa-pencil-alt pointer" title="Edit Name" v-b-tooltip.hover @click="editDataType(field)"></i>
                                    <i v-if="!field.in_use" class="far fa-trash-alt pointer" title="Delete Data Type" v-b-tooltip.hover @click="delDataType(field)"></i>
                                </span>
                            </b-list-group-item>
                        </b-list-group>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New Data Type</div>
                        <ValidationObserver v-slot="{handleSubmit}" ref="create-validator">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitCreateForm)" novalidate>
                                    <text-input v-model="createForm.name" rules="required" label="Name" name="name"></text-input>
                                    <submit-button button_text="Create Data Type" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <b-modal
            ref="edit-data-type-modal"
            title="Edit Data Type Name"
            hide-footer
            @hidden="resetEditForm"
        >
            <ValidationObserver v-slot="{handleSubmit}">
                <b-overlay :show="submitted">
                    <template #overlay>
                        <form-loader text="Processing..."></form-loader>
                    </template>
                    <b-form @submit.prevent="handleSubmit(submitEditForm)" novalidate>
                        <text-input v-model="editForm.name" rules="required" label="Name" name="name"></text-input>
                        <submit-button button_text="Update Data Type" :submitted="submitted" class="mt-3" />
                    </b-form>
                </b-overlay>
            </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            data_list: {
                type: Array,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                editForm: this.$inertia.form({
                    name:    null,
                    type_id: null,
                }),
                createForm: this.$inertia.form({
                    name:    null,
                }),
            }
        },
        methods: {
            editDataType(field)
            {
                this.editForm.name    = field.name;
                this.editForm.type_id = field.type_id
                this.$refs['edit-data-type-modal'].show();
            },
            resetEditForm()
            {
                this.editForm.reset();
            },
            submitEditForm()
            {
                this.submitted = true;
                this.editForm.put(route('data-types.update', this.editForm.type_id), {
                    onFinish: ()=> {
                        console.log('done');
                        this.submitted = false;
                        this.$refs['edit-data-type-modal'].hide();
                    }
                })
            },
            submitCreateForm()
            {
                this.submitted = true;
                this.createForm.post(route('data-types.store'), {
                    onFinish: ()=> {
                        this.submitted = false;
                        this.createForm.reset();
                        this.$refs['create-validator'].reset();
                    }
                });
            },
            delDataType(field)
            {
                this.$bvModal.msgBoxConfirm('Please Verify',
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
                        this.$inertia.delete(route('data-types.destroy', field.type_id));
                    }
                });
            }
        }
    }
</script>

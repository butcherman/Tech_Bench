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
                                    value-field="name"
                                    placeholder="Select A Category This Equipment Belongs To"
                                    rules="required"
                                    :options="categories">
                                </dropdown-input>
                                <text-input v-model="form.name" label="Equipment Name" rules="required|no-special" name="name" placeholder="Enter A Unique Name for the Equipment"></text-input>
                                <fieldset>
                                    <label>Customer Information to Gather:</label>
                                </fieldset>
                                <draggable animation="200" :list="form.system_data_fields">
                                    <b-input-group v-for="(index, key) in form.data_fields" :key="index.name" class="my-2">
                                        <b-input-group-prepend class="align-middle d-block mr-1">
                                            <i class="fas fa-sort align-middle pointer" title="Drag to Change Order" v-b-tooltip.hover></i>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            v-model="index.name"
                                            type="text"
                                            list="data-list"
                                            placeholder="Input information to gather for the customer"
                                            autocomplete="false"
                                            disabled
                                        ></b-form-input>
                                        <b-input-group-append class="align-middle d-block ml-1">
                                            <i class="far fa-times-circle text-danger pointer" title="Remove this Option" v-b-tooltip.hover @click="delOption(key)"></i>
                                        </b-input-group-append>
                                    </b-input-group>
                                    <b-input-group v-for="index in fields" :key="index" class="my-2">
                                        <b-input-group-prepend class="align-middle d-block mr-1">
                                            <i class="fas fa-sort align-middle pointer" title="Drag to Change Order" v-b-tooltip.hover></i>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            v-model="form.new_data_fields[index]"
                                            type="text"
                                            list="data-list"
                                            placeholder="Input information to gather for the customer"
                                            autocomplete="false"
                                        ></b-form-input>
                                        <b-input-group-append class="align-middle d-block ml-1">
                                            <i class="far fa-times-circle text-danger pointer" title="Remove this Option" v-b-tooltip.hover @click="delNewOption(index)"></i>
                                        </b-input-group-append>
                                    </b-input-group>
                                </draggable>
                                <div>
                                    <b-button class="float-right my-2" variant="warning" @click="fields++"><i class="fas fa-plus"></i> Add Row</b-button>
                                </div>
                                <datalist id="data-list">
                                    <option v-for="data in dataList" :key="data">{{data}}</option>
                                </datalist>
                                <submit-button button_text="Save Changes" :submitted="submitted"></submit-button>
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
            categories: {
                type:     Array,
                required: true,
            },
            dataList: {
                type:     Array,
                required: true,
            },
            equipment: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: {
                    cat_id:          this.equipment.equipment_category.name,
                    name:            this.equipment.name,
                    data_fields:     this.equipment.data_field_type,
                    new_data_fields: [],
                },
                fields: 1,
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
            submitForm()
            {
                console.log(this.form);
                this.submitted = true;
                this.$inertia.put(route('admin.equipment.update', this.equipment.equip_id), this.form);
            },
            delOption(index)
            {
                this.$bvModal.msgBoxConfirm('This information already gathered will be deleted', {
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
                        this.form.data_fields.splice(index, 1);

                        this.$bvModal.msgBoxOk('Settings will not be perminate until you save the changes', {
                            title:          'Save Changes to Commit',
                            size:           'sm',
                            buttonSize:     'sm',
                            footerClass:    'p-2',
                            hideHeaderClose: false,
                            centered:        true
                        })
                    }
                });
            },
            delNewOption(index)
            {
                this.form.new_data_fields.splice(index, 1);
                this.fields--;
            }
        }
    }
</script>

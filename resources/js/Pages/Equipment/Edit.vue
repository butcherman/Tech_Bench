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
                                    v-model="form.category"
                                    label="Select Category"
                                    name="category"
                                    text-field="name"
                                    value-field="name"
                                    placeholder="Select A Category This Equipment Belongs To"
                                    rules="required"
                                    :options="cat_list">
                                </dropdown-input>
                                <text-input v-model="form.name" label="Equipment Name" rules="required|no-special" name="name" placeholder="Enter A Unique Name for the Equipment"></text-input>
                                <fieldset>
                                    <label>Customer Information to Gather:</label>
                                </fieldset>
                                <draggable animation="200" :list="form.data_fields">
                                    <b-input-group v-for="(name, index) in form.data_fields" :key="index" class="my-2">
                                        <b-input-group-prepend class="align-middle d-block mr-1">
                                            <i class="fas fa-sort align-middle pointer" title="Drag to Change Order" v-b-tooltip.hover></i>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            v-model="form.data_fields[index]"
                                            type="text"
                                            list="data-list"
                                            placeholder="Input information to gather for the customer"
                                            autocomplete="false"
                                            :disabled="isNewOption(name)"
                                        ></b-form-input>
                                        <b-input-group-append class="align-middle d-block ml-1">
                                            <i class="far fa-times-circle text-danger pointer" title="Remove this Option" v-b-tooltip.hover @click="delOption(name, index)"></i>
                                        </b-input-group-append>
                                    </b-input-group>
                                </draggable>
                                <div>
                                    <b-button class="float-right my-2" variant="warning" @click="addRow"><i class="fas fa-plus"></i> Add Row</b-button>
                                </div>
                                <datalist id="data-list">
                                    <option v-for="data in data_list" :key="data">{{data}}</option>
                                </datalist>
                                <submit-button button_text="Save Changes" :submitted="submitted"></submit-button>
                            </b-form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!in_use" class="row gris-margin justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <b-button variant="danger" block @click="deleteEquipment">Delete Equipment</b-button>
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
            /**
             * Simple array with list of names of Equipment Categories
             */
            cat_list: {
                type:     Array,
                required: true,
            },
            /**
             * Current equipment data types that are listed in the database
             * to autofill the text input datalist
             */
            dataList: {
                type:     Array,
                required: true,
            },
            /**
             * Object collection from /app/Models/EquipmentType
             */
            equipment: {
                type:     Object,
                required: true,
            },
            /**
             * Boolean value noting if any customers or Tech Tips are currently tied to this equipment ID
             */
            in_use: {
                type:     Boolean,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    category:    this.equipment.equipment_category.name,
                    name:        this.equipment.name,
                    data_fields: [],
                    del_fields:  []
                }),
                data_list: this.dataList,
            }
        },
        created() {
            this.setValues();
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('equipment.update', this.equipment.equip_id));
            },
            /**
             * Remove an existing Data type
             */
            delOption(name, index)
            {
                if(name)
                {
                    this.$bvModal.msgBoxConfirm('This information already gathered will be deleted',
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
                            this.form.data_fields.splice(index, 1);
                            this.form.del_fields.push(name);

                            this.$bvModal.msgBoxOk('Settings will not be perminate until you save the changes', {
                                title:          'Save Changes to Commit',
                                size:           'sm',
                                buttonSize:     'sm',
                                footerClass:    'p-2',
                                hideHeaderClose: false,
                                centered:        true,
                            });
                        }
                    });
                }
                else
                {
                    this.form.data_fields.splice(name, 1);
                }
            },
            /**
             * Since the equipment data fiels may be modified, set them to a variable so that we are not trying to modify the prop directly
             */
            setValues()
            {
                for(var i=0; i < this.equipment.data_field_type.length; i++)
                {
                    this.form.data_fields.push(this.equipment.data_field_type[i].name);
                    this.data_list = this.arrayRemove(this.data_list, this.equipment.data_field_type[i].name)

                }
            },
            /**
             * Remove a value from an array.  duh!
             */
            arrayRemove(arr, value)
            {
                return arr.filter(function(el)
                {
                    return el != value;
                });
            },
            /**
             * Determine if the data field is something brand new that needs to be entered into the database, or an existing fielsd
             */
            isNewOption(opt)
            {
                var index = this.equipment.data_field_type.find(el => el.name === opt);
                return index ? true : false;
            },
            /**
             * Add a blank row to the data type list that can be assigned as a data type
             */
            addRow()
            {
                this.form.data_fields.push(null);
            },
            /**
             * Delete the equipment being edited.
             * Note:  this option is hidden if the equipment is in use
             */
            deleteEquipment()
            {
                this.$bvModal.msgBoxConfirm('Are you sure you want to delete this Equipment?', {
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
                        this.$inertia.delete(this.route('equipment.destroy', this.equipment.equip_id));
                    }
                });
            }
        }
    }
</script>

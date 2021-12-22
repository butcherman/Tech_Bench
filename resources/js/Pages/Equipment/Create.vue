<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">New Equipment Type</h4>
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
                                    <dropdown-input
                                        v-model="form.category"
                                        label="Equipment Category"
                                        name="category"
                                        text-field="name"
                                        value-field="name"
                                        placeholder="Select A Category This Equipment Belongs To"
                                        rules="required"
                                        :options="cat_list"
                                    ></dropdown-input>
                                    <text-input v-model="form.name" label="Equipment Name" rules="required|no-special" name="name" placeholder="Enter A Unique Name for the Equipment"></text-input>
                                    <fieldset>
                                        <label>Customer Information to Gather:</label>
                                    </fieldset>
                                    <draggable animation="200" :list="form.system_data_fields">
                                        <b-input-group v-for="index in fields" :key="index" class="my-2">
                                            <b-input-group-prepend class="align-middle d-block mr-1">
                                                <i class="fas fa-sort align-middle pointer" title="Drag to Change Order" v-b-tooltip.hover></i>
                                            </b-input-group-prepend>
                                            <b-form-input
                                                v-model="form.data_fields[index]"
                                                type="text"
                                                list="data-list"
                                                placeholder="Input information to gather for the customer"
                                                autocomplete="false"
                                            ></b-form-input>
                                            <b-input-group-append class="align-middle d-block ml-1">
                                                <i class="far fa-times-circle text-danger pointer" title="Remove this Option" v-b-tooltip.hover @click="delOption(index)"></i>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </draggable>
                                    <div>
                                        <b-button class="float-right my-2" variant="warning" @click="fields++"><i class="fas fa-plus"></i> Add Row</b-button>
                                    </div>
                                    <datalist id="data-list">
                                        <option v-for="data in data_list" :key="data">{{data}}</option>
                                    </datalist>
                                    <submit-button button_text="Create Equipment" :submitted="submitted" class="mt-3" />
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
    import Draggable from 'vuedraggable';

    export default {
        layout: App,
        components: { Draggable },
        props: {
            category: {
                type:     String,
                required: true,
            },
            cat_list: {
                type:     Array,
                required: true,
            },
            data_list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    category: this.category,
                    name: null,
                    data_fields: [],
                }),
                fields: 5,
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('equipment.store'));
            },
            delOption(index)
            {
                this.form.data_fields.splice(index, 1);
                this.fields--;
            }
        }
    }
</script>

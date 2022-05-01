<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Equipment and Categories</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Categories</div>
                        <div>
                            <div class="text-center">
                                <small>Select a Category to see the Equipment types</small>
                            </div>
                            <b-list-group>
                                <b-list-group-item v-for="cat in categories" :key="cat.cat_id" class="equipment-list d-flex justify-content-between align-items-center pointer" @click="populateEquipment(cat)">
                                    {{cat.name}}
                                    <inertia-link as="b-badge" :href="route('equipment-categories.edit', cat.cat_id)" variant="warning" pill title="Edit Name" v-b-tooltip.hover><i class="fas fa-pencil-alt"></i></inertia-link>
                                </b-list-group-item>
                                <b-list-group-item class="text-center">
                                    <inertia-link as="b-button" :href="route('equipment-categories.create')" variant="success">Create New Category</inertia-link>
                                </b-list-group-item>
                            </b-list-group>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Types</div>
                        <div class="text-center">
                                <small>Select an Equipment Type to modify it</small>
                            </div>
                        <b-list-group>
                            <inertia-link as="b-list-group-item" v-for="equip in equipment" :key="equip.equip_id" :href="route('equipment.edit', equip.equip_id)" class="equipment-list pointer">
                                {{equip.name}}
                            </inertia-link>
                            <b-list-group-item class="text-center" v-if="selected_category !== null">
                                <inertia-link as="b-button" :href="route('equipment.show', selected_category)" variant="success">Create New Equipment</inertia-link>
                            </b-list-group-item>
                            <b-list-group-item class="text-center" v-if="selected_category !== null && equipment.length == 0">
                                <b-button variant="danger" @click="destroyCategory">Delete this Category</b-button>
                            </b-list-group-item>
                        </b-list-group>
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
             * Array of objectes from /app/Models/EquipmentCategory
             */
            categories: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                equipment:         [],
                selected_category: null,
            }
        },
        methods: {
            /**
             * List all of the existing equipment assigned to the selected category
             */
            populateEquipment(category)
            {
                this.equipment         = category.equipment_type;
                this.selected_category = category.cat_id;
            },
            /**
             * Completely remove a category from the DB
             * Note - this option is only availble if no equipment is assigned to the category
             */
            destroyCategory()
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
                        this.$inertia.delete(route('equipment-categories.destroy', this.selected_category), {
                            onFinish: ()=> {
                                this.equipment         = [];
                                this.selected_category = null;
                            }
                        });
                    }
                });
            }
        }
    }
</script>

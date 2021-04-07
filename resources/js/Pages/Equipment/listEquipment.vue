<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Edit Equipment Categories</h4>
            </div>
        </div>
        <div class="row justify-content-center grid-margin">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Select Equipment to Edit</div>
                        <ul class = "list-group">
                            <li class = "list-group-item" v-for="cat in equipmentList" :key="cat.cat_id">
                                <h4 class = "list-group-item-heading">{{cat.name}}</h4>
                                <p class = "list-group-item-text ml-4" v-for="type in cat.equipment_type" :key="type.equip_id">
                                    <inertia-link :href="route('admin.equipment.edit', cat.cat_id)">
                                        {{type.name}}
                                    </inertia-link>
                                </p>
                            </li>
                        </ul>
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
            equipmentList: {
                type:     Array,
                required: true,
            }
        },
    }
</script>

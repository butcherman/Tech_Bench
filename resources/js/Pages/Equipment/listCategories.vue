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
                        <div class="card-title">Select Category to Edit</div>
                        <b-list-group>
                            <b-list-group-item class="text-center">
                                <inertia-link v-for="cat in categories" :key="cat.cat_id" as="b-button" :href="route('admin.equipment.categories.edit', cat.cat_id)" variant="outline-primary" block>{{cat.name}}</inertia-link>
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
            categories: {
                type:     Array,
                required: true,
            }
        },
    }
</script>

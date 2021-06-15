<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Tech Tips</h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <b-form @submit.prevent="search">
                            <b-input-group>
                                <b-form-input type="text" placeholder="Search Tips..." autofocus v-model="form.search.text"></b-form-input>
                                <b-input-group-append>
                                    <b-button type="submit" variant="primary" ><span class="fas fa-search"></span> <span class="d-none d-sm-inline">Search</span></b-button>
                                </b-input-group-append>
                                <b-input-group-append v-if="permissions.create">
                                    <inertia-link :href="route('tech-tips.create')" class="btn btn-warning d-none d-sm-block"><span class="fas fa-plus"></span> <span class="d-none d-sm-inline">Create New</span></inertia-link>
                                </b-input-group-append>
                            </b-input-group>
                            <div v-if="permissions.create" class="text-center mt-2 d-sm-none">
                                <a :href="route('tech-tips.create')" class="btn btn-warning"><span class="fas fa-plus"></span> <span>Create New</span></a>
                            </div>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-lg-3 col-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Filter Options
                            <b-button size="sm" class="float-right d-block d-lg-none" v-b-toggle.filter-options-collapse><i class="fas fa-bars"></i></b-button>
                        </div>
                        <b-collapse id="filter-options-collapse" is-nav visible>
                            <b-overlay :show="showOverlay">
                                <template #overlay>
                                    <atom-loader></atom-loader>
                                </template>
                                <div>
                                    <h6 class="mt-4 mb-2">Article Type:</h6>
                                    <b-form-group>
                                        <b-form-checkbox-group
                                            v-model="form.search.type"
                                            :options="filter_data.tip_types"
                                            text-field="description"
                                            value-field="tip_type_id"
                                            stacked
                                            @change="search"
                                        ></b-form-checkbox-group>
                                    </b-form-group>
                                    <h6 class="mt-4 mb-2">Equipment Type:</h6>
                                    <b-form-group v-for="cat in filter_data.equip_types" :key="cat.cat_id" :label="cat.name">
                                        <b-form-checkbox
                                            v-for="equip in cat.equipment_type"
                                            v-model="form.search.equip_id"
                                            name="equipment_type"
                                            :key="equip.equip_id"
                                            :value="equip.equip_id"
                                            stacked
                                            @change="search"
                                        >{{equip.name}}</b-form-checkbox>
                                    </b-form-group>
                                </div>
                                <b-button variant="info" block @click="resetFilters">Reset Filters</b-button>
                            </b-overlay>
                        </b-collapse>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        results here
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
            filter_data: {
                type:     Object,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                showOverlay: false,
                loading    : false,
                error      : false,
                form       : {
                    search: {
                        text  :   null,
                        type  :   [],
                        equip_id: [],
                    },
                    pagination: {
                        rows   : '',
                        low    : '',
                        high   : '',
                        perPage: 10
                    },
                    page: 1,
                },
                tips      : [],
                resPerPage: [10, 25, 50, 100],
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
            search()
            {
                console.log(this.form);



            },
            resetFilters()
            {
                this.form = {
                    search: {
                        text  : null,
                        type  : [],
                        sys_id: [],
                    },
                    pagination: {
                        rows   : '',
                        low    : '',
                        high   : '',
                        perPage: 10
                    },
                    page: 1,
                }
                this.search();
            }
        }
    }
</script>

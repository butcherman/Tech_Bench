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
                                <b-input-group-append>
                                    <inertia-link :href="route('tech-tips.create')" class="btn btn-warning d-none d-sm-block"><span class="fas fa-plus"></span> <span class="d-none d-sm-inline">Create New</span></inertia-link>
                                </b-input-group-append>
                            </b-input-group>
                            <div class="text-center mt-2 d-sm-none">
                                <a :href="route('tech-tips.create')" class="btn btn-warning"><span class="fas fa-plus"></span> <span>Create New</span></a>
                            </div>
                        </b-form>
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
            //
        },
        data() {
            return {
                showOverlay: false,
                loading    : false,
                error      : false,
                form       : {
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
            //
        }
    }
</script>

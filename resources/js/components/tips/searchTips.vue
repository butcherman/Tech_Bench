<template>
    <div>
        <div class="row grid-margin">
            <div class="col-12 grid-margin-stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-form @submit.prevent="search" id="tech-tip-search-form">
                            <b-input-group>
                                <b-form-input type="text" placeholder="Search Tips..." autofocus v-model="form.search.text"></b-form-input>
                                <b-input-group-append>
                                    <b-button type="submit" variant="primary" ><span class="fas fa-search"></span> <span class="d-none d-sm-inline">Search</span></b-button>
                                </b-input-group-append>
                                <b-input-group-append v-if="can_create">
                                    <a :href="route('tips.create')" class="btn btn-warning d-none d-sm-block"><span class="fas fa-plus"></span> <span class="d-none d-sm-inline">Create New</span></a>
                                </b-input-group-append>
                            </b-input-group>
                            <div class="text-center mt-2 d-sm-none">
                                <a href="#" class="btn btn-warning"><span class="fas fa-plus"></span> <span>Create New</span></a>
                            </div>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-lg-2 col-md-3 col-12 grid-margin stretch-card">
                <b-card>
                    <template v-slot:header>
                        <h5 class="mb-0">
                            Filter Options
                            <b-button size="sm" class="float-right d-block d-lg-none" v-b-toggle="'collapse-me'"><i class="fas fa-bars"></i></b-button>
                        </h5>
                    </template>
                    <b-card-text>
                        <b-collapse id="collapse-me" is-nav visible class="w-100">
                            <div class="w-100 filter-wrapper">
                                <b-overlay :show="showOverlay">
                                    <template v-slot:overlay>
                                        <atom-spinner
                                            :animation-duration="1000"
                                            :size="60"
                                            color="#ff1d5e"
                                            class="mx-auto"
                                        />
                                        <h4 class="text-center">Processing</h4>
                                    </template>
                                    <h6 class="mt-4 mb-2">Article Type:</h6>
                                    <b-form-group>
                                        <b-form-checkbox-group
                                            v-model="form.search.type"
                                            :options="tip_types"
                                            text-field="description"
                                            value-field="tip_type_id"
                                            @change="search"
                                            stacked
                                        ></b-form-checkbox-group>
                                    </b-form-group>
                                    <h6 class="mt-4 mb-2">Equipment Type:</h6>
                                    <b-form-group v-for="cat in equipment" :key="cat.cat_id" :label="cat.name">
                                        <b-form-checkbox
                                            v-for="sys in cat.system_types"
                                            v-model="form.search.sys_id"
                                            name="equipment_type"
                                            :key="sys.sys_id"
                                            :value="sys.sys_id"
                                            @change="search"
                                            stacked
                                        >{{sys.name}}</b-form-checkbox>
                                    </b-form-group>
                                    <b-button variant="info" block @click="resetFilters">Reset Filters</b-button>
                                </b-overlay>
                            </div>
                        </b-collapse>
                    </b-card-text>
                </b-card>
            </div>
            <div class="col-lg-10 col-md-9 col-12 grid-margin stretch-card">
                <b-overlay :show="loading">
                    <template v-slot:overlay>
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Processing</h4>
                    </template>
                    <div class="row h-100" v-if="error">
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="/images/errors/sry_error.png" alt="Error Image" />
                                    <div class="mt-4 text-danger">
                                        <p>
                                            Something bad happend.
                                        </p>
                                        <p>
                                            A log has been generated and our minions are busy at work to determine what went wrong.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row h-100" v-else-if="loading">
                        <div class="col-12"></div>
                    </div>
                    <div class="row h-100" v-else-if="!tips.length">
                        <div class="col-12">
                            <div class="card h-100  grid-margin stretch-card">
                                <div class="card-body text-center">
                                    <img src="/images/errors/search.png" alt="Error Image" />
                                    <div class="mt-4">
                                        <p>
                                            It seems that there are no Tech Tips with your search criteria.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else v-for="tip in tips" :key="tip.tip_id" class="row grid-margin tip-link">
                        <div class="col-12">
                            <a :href="route('tips.details', [tip.tip_id, dashify(tip.subject)])" class="w-100 text-dark">
                                <div class="card">
                                    <div class="card-header">
                                        {{tip.subject}}
                                        <b-badge pill variant="info" size="sm" class="float-right">{{tip.tech_tip_types.description}}</b-badge>
                                    </div>
                                    <div class="card-body">
                                        <div v-html="tip.summary" class="tip-link"></div>
                                        <strong>For Equipment: </strong>
                                        <b-badge pill variant="primary" v-for="sys in tip.system_types" :key="sys.sys_id" class="ml-1 mb-1">{{sys.name}}</b-badge>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </b-overlay>
                <div class="row">
                    <div class="col-sm-3 text-center text-sm-left mb-2">
                        Showing {{form.pagination.low}} through {{form.pagination.high}} of {{form.pagination.rows}}
                    </div>
                    <div class="col-sm-6">
                        <b-pagination
                            v-model="form.page"
                            :total-rows="form.pagination.rows"
                            :per-page="form.pagination.perPage"
                            next-text="Next"
                            prev-text="Prev"
                            align="center"
                            @change="updatePage"
                        ></b-pagination>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col text-center">Results Per Page</div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <b-badge pill :variant="form.pagination.perPage == num ? 'success' : 'primary'" class="ml-1 mb-1 pointer" v-for="num in resPerPage" :key="num" @click="updatePerPage(num)">{{num}}</b-badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            can_create: {
                type    : Boolean,
                required: false,
                default : false,
            },
            tip_types: {
                type    : Array,
                required: true,
            },
            equipment: {
                type    : Array,
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
        mounted() {
            this.search(false);
        },
        methods: {
            search(showSide = true)
            {
                if(showSide)
                {
                    this.showOverlay = true;
                }
                this.loading = true;
                axios.get(this.route('tips.search', this.form))
                    .then(res => {
                        this.form.page            = res.data.current_page;
                        this.form.pagination.rows = res.data.total;
                        this.form.pagination.low  = res.data.from;
                        this.form.pagination.high = res.data.to;
                        this.tips                 = res.data.data;
                        this.showOverlay          = false;
                        this.loading              = false;
                    }).catch(error => {
                        this.error       = true
                        this.loading     = false;
                        this.showOverlay = false;
                    });
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
            },
            updatePage(newPage)
            {
                this.form.page = newPage;
                this.search();
            },
            updatePerPage(perPage)
            {
                this.form.pagination.perPage = perPage;
                this.form.page               = 1;
                this.search();
            },
        }
    }
</script>

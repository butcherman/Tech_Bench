<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-form @submit="searchTips" id="tech-tip-search-form">
                            <b-input-group>
                                <b-form-input type="text" placeholder="Search Tips..." autofocus v-model="form.search.searchText"></b-form-input>
                                <b-input-group-append>
                                    <b-button type="submit" variant="primary" ><span class="fas fa-search"></span> <span class="d-none d-sm-inline">Search</span></b-button>
                                </b-input-group-append>
                                <b-input-group-append v-if="can_create">
                                    <a :href="route('tips.create')" class="btn btn-warning"><span class="fas fa-plus"></span> <span class="d-none d-sm-inline">Create New</span></a>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-12 grid-margin stretch-card">
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
                                <h6 class="mt-4 mb-2">Article Type:</h6>
                                <b-form-group>
                                    <b-form-checkbox-group
                                        v-model="form.search.articleType"
                                        :options="tip_types"
                                        stacked
                                        @change="updateSearch"
                                    ></b-form-checkbox-group>
                                </b-form-group>
                                <h6 class="mt-4 mb-2">Equipment Type:</h6>
                                <b-form-group v-for="cat in sys_types" :key="cat.cat_id" :label="cat.name">
                                    <b-form-checkbox
                                        v-for="sys in cat.system_types"
                                        v-model="form.search.systemType"
                                        :key="sys.sys_id"
                                        :value="sys.sys_id"
                                        name="equipment_type"
                                        stacked
                                        @change="updateSearch"
                                    >{{sys.name}}</b-form-checkbox>
                                </b-form-group>
                                <b-button variant="info" block @click="resetFilters">Reset Filters</b-button>
                            </div>
                        </b-collapse>
                    </b-card-text>
                </b-card>
            </div>
            <div class="col-md-10">
                <b-overlay :show="loading" class="h-100">
                    <template v-slot:overlay>
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Loading Tech Tips</h4>
                    </template>
                    <div class="row h-100" v-if="error">
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="/img/err_img/sry_error.png" alt="Error Image" />
                                    <div class="mt-4 text-danger">
                                        <p>
                                            Sorry, but something bad happend.
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
                                    <img src="/img/err_img/search.png" alt="Error Image" />
                                    <div class="mt-4">
                                        <p>
                                            It seems that there are no Tech Tips with your search criteria.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-12 grid-margin  tip-link" v-for="tip in tips" :key="tip.tip_id">
                            <a :href="route('tip.details', [tip.tip_id, dashify(tip.subject)])" class="w-100 text-dark" title="Click to See Rest of Tip" v-b-tooltip.hover>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>{{tip.subject}}</strong>
                                        <span class="float-sm-right text-secondary d-block d-sm-inline">{{tip.created_at}}</span>
                                        </div>
                                    <div class="card-body">
                                        <div v-html="tip.description" class="mb-3 tip-preview"></div>
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
    props: [
        'tip_types',
        'sys_types',
        'can_create',
    ],
    data () {
        return {
            error:   false,
            loading: true,
            form: {
                search: {
                    searchText:  '',
                    articleType: [],
                    systemType:  [],
                },
                pagination: {
                    rows:    '',
                    low:     '',
                    high:    '',
                    perPage: 10
                },
                page: 1,
            },
            filter_types: [],
            system_types: [],
            tips:         [],
            resPerPage:   [10, 25, 50, 100],
        }
    },
    mounted()
    {
        this.updateSearch();
    },
    methods: {
        searchTips(e)
        {
            e.preventDefault();
            this.updateSearch();
        },
        updateSearch()
        {
            this.loading = true;
            axios.get(this.route('tip.search', this.form))
                .then(res => {
                    this.form.page            = res.data.meta.current_page;
                    this.form.pagination.rows = res.data.meta.total;
                    this.form.pagination.low  = res.data.meta.from;
                    this.form.pagination.high = res.data.meta.to;
                    this.tips                 = res.data.data;
                    this.loading              = false;
                }).catch(error => this.error = true);
        },
        resetFilters()
        {
            console.log('triggered');
            this.form.search.searchText  = null;
            this.form.search.articleType = [];
            this.form.search.systemType  = [];
            this.updateSearch();
        },
        updatePage(newPage)
        {
            this.form.page = newPage;
            this.updateSearch();
        },
        updatePerPage(num)
        {
            this.form.pagination.perPage = num;
            this.updateSearch();
        }
    }
}
</script>

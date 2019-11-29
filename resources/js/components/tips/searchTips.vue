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
                                    <b-button type="submit" variant="primary" ><span class="ti-search"></span> Search</b-button>
                                </b-input-group-append>
                                <b-input-group-append v-if="can_create">
                                    <a :href="route('tips.create')" class="btn btn-warning"><span class="ti-plus"> Create New</span></a>
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
                            <b-button size="sm" class="float-right d-block d-lg-none" v-b-toggle="'collapse-me'"><i class="ti-menu"></i></b-button>
                        </h5>
                    </template>
                    <b-card-text>
                        <b-collapse id="collapse-me" is-nav visible class="w-100">
                            <div class="w-100">
                                <h6 class="mt-4 mb-2">Article Type</h6>
                                <b-form-group>
                                    <b-form-checkbox-group
                                        v-model="form.search.articleType"
                                        :options="tip_types"
                                        stacked
                                        @change="updateSearch"
                                    ></b-form-checkbox-group>
                                </b-form-group>
                                <h6 class="mt-4 mb-2">Equipment Type</h6>
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
                                <!-- <b-button variant="info" block @click="resetFilters">Reset Filters</b-button> TODO:  Fix reset button -->
                            </div>
                        </b-collapse>
                    </b-card-text>
                </b-card>
            </div>
            <div class="col-md-10">
                <div v-if="loading" class="loading">
                    <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                </div>
                <div v-else>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card" v-for="tip in tips" :key="tip.tip_id">
                            <div class="card">
                                <a :href="route('tip.details', [tip.tip_id, dashify(tip.subject)])" class="text-dark">
                                    <div class="card-header">
                                    {{tip.subject}}
                                    <span class="float-right">{{tip.created_at}}</span>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <div v-html="tip.description" class="mb-3"></div>
                                    <b-badge pill variant="primary" v-for="sys in tip.system_types" :key="sys.sys_id" class="ml-1 mb-1">{{sys.name}}</b-badge>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <span class="float-right">Showing {{form.pagination.low}} through {{form.pagination.high}} of {{form.pagination.rows}}</span>
                            <b-pagination
                                v-model="form.page"
                                :total-rows="form.pagination.rows"
                                :per-page="form.pagination.perPage"
                                next-text="Next >"
                                prev-text="< Previous"
                                class="mx-auto"
                                @change="updatePage"
                            ></b-pagination>
                            <span class="float-left">
                                Results Per Page
                                <b-badge pill variant="primary" class="ml-1 mb-1 pointer" v-for="num in resPerPage" :key="num" @click="updatePerPage(num)">{{num}}</b-badge>
                            </span>
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
    created()
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
            window.scrollTo(0, 0);
            axios.get(this.route('tip.search', this.form))
                .then(res => {
                    console.log(res);
                    this.form.page            = res.data.meta.current_page;
                    this.form.pagination.rows = res.data.meta.total;
                    this.form.pagination.low  = res.data.meta.from;
                    this.form.pagination.high = res.data.meta.to;
                    this.tips                 = res.data.data;
                    this.loading              = false;
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
        },
        resetFilters()
        {
            this.form.articleType = [];
            this.form.systemType  = [];
            this.form.searchText  = '';
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

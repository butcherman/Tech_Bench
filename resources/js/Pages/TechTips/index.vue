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
                                <b-form-input type="text" placeholder="Search Tips..." autofocus v-model="form.search_text"></b-form-input>
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
                                            v-model="form.search_type"
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
                                            v-model="form.search_equip_id"
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
                        <b-table :items="tech_tips" :fields="fields" striped responsive :busy="loading" show-empty>
                            <template #empty>
                                <h5 class="text-center">Nothing to See Here</h5>
                                <p class="text-center">No Tech Tips found.  Try searching for something else</p>
                            </template>
                            <template #table-busy>
                                <atom-loader></atom-loader>
                            </template>
                            <template #cell(subject)="data">
                                <inertia-link :href="route('tech-tips.show', data.item.slug)">{{data.item.subject}}</inertia-link>
                            </template>
                            <template #cell(preview)="data">
                                <i class="pointer fas" :class="data.detailsShowing ? 'fa-eye-slash' : 'fa-eye'" :title="data.detailsShowing ? 'Hide Preview' : 'Show Preview'" v-b-tooltip.hover @click="data.toggleDetails"></i>
                            </template>
                            <template #cell(pinned)="data">
                                <i v-if="data.item.sticky" class="fas fa-thumbtack text-danger" title="Pinned Tip" v-b-tooltip.hover></i>
                            </template>
                            <template #row-details="data">
                                <div v-html="data.item.summary"></div>
                                <div>
                                    <strong>For Equipment:</strong>
                                    <b-badge pill variant="primary" v-for="equip in data.item.equipment_type" :key="equip.equip_id">{{equip.name}}</b-badge>
                                </div>
                            </template>
                        </b-table>
                        <div class="row">
                            <div class="col-sm-3 text-center text-sm-left">
                                {{results.low}} through {{results.high}} of {{results.total}}
                            </div>
                            <div class="col-sm-6">
                                <b-pagination
                                    v-model="form.page"
                                    :total-rows="results.total"
                                    :per-page="form.pagination_perPage"
                                    next-text="Next"
                                    prev-text="Prev"
                                    align="center"
                                    @change="updatePage"
                                ></b-pagination>
                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col text-center">
                                        Results Per Page
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <b-badge
                                        pill
                                        class="pointer ml-1 mb-1"
                                        v-for="num in results.per_page"
                                        :key="num"
                                        :variant="form.pagination_perPage == num ? 'success' : 'primary'"
                                        @click="updatePerPage(num)"
                                        >
                                            {{num}}
                                        </b-badge>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                loading:     false,
                error:       false,
                tech_tips:   [],
                results: {
                    total:     0,
                    low:       0,
                    high:      0,
                    per_page: [10, 25, 50, 100],
                },
                form: {
                    search_text:        null,
                    search_type:        [],
                    search_equip_id:    [],
                    pagination_perPage: 10,
                    page:               1,
                },
                fields: [
                    {
                        key:     'pinned',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'preview',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'subject',
                        label:   'Subject',
                        sortable: true,
                    },
                    {
                        key:     'created_at',
                        label:   'Date',
                        sortable: true,
                    },
                ]
            }
        },
        mounted()
        {
            this.search();
        },
        methods: {
            search()
            {
                this.loading = true;
                axios.get(this.route('tips.search', this.form))
                    .then(res => {
                        this.tech_tips     = res.data.data;
                        this.results.total = res.data.total;
                        this.results.low   = res.data.from;
                        this.results.high  = res.data.to;
                        this.loading       = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            resetFilters()
            {
                this.form = {
                    search_text:        null,
                    search_type:        [],
                    search_equip_id:    [],
                    pagination_perPage: 10,
                    page:               1,
                }
                this.search();
            },
            updatePage(newPage)
            {
                this.form.page = newPage;
                this.search();
            },
            updatePerPage(num)
            {
                this.form.pagination_perPage = num;
                this.search();
            }
        },
    }
</script>

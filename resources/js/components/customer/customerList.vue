<template>
    <div class="table">
        <div v-if="error">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-4">
                    <img src="/img/err_img/confused.png" alt="Error" id="header-logo" />
                </div>
                <div class="col-md-5">
                    <h3 class="text-danger">Something Bad Happened:</h3>
                    <p>
                        Sorry about that.
                    </p>
                    <p>
                        Our minions are busy at work to determine what happened.
                    </p>
                </div>
            </div>
        </div>
        <vue-good-table
            v-else
            mode="remote"
            ref="customer-list-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :sort-options="{enabled:true}"
            :isLoading.sync="isLoading"
            :totalRows="pagination.meta.total"
            :pagination-options="{
                enabled: true,
                mode: 'records',
                perPage: searchParam.perPage,
                position: 'bottom',
                perPageDropdown: [25, 50, 100, 250],
                dropdownAllowAll: true,
            }"
            @on-page-change="newPage"
            @on-sort-change="reSort"
            @on-per-page-change="perPageUpdate"
            @on-column-filter="searchFilter"
        >
            <div slot="table-actions">
                <!-- <b-button variant="warning" @click="resetSearch" pill size="sm"><i class="fas fa-sync-alt" aria-hidden="true"></i> Reset Search Filters</b-button> -->
                <b-button v-if="allow_create" variant="info" :href="route('customer.id.create')" pill size="sm"><i class="fas fa-plus" aria-hidden="true"></i> Add New Customer</b-button>
            </div>
            <div slot="emptystate">
                <h4 v-if="loadDone" class="text-center">No Customers Available</h4>
                <atom-spinner v-else
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <div slot="loadingContent">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                Loading Customers...
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'system_list'">
                    <div v-for="sys in data.row.customer_systems" :key="sys.cust_sys_id">{{sys.sys_name}}</div>
                </span>
                <span v-else-if="data.column.field == 'name'" class="d-block w-100 h-100">
                    <a :href="route('customer.details', [data.row.cust_id, dashify(data.row.name)])" class="d-block w-100 h-100 text-dark">{{data.row.name}}</a>
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
export default {
    props: [
        'system_types',
        'allow_create',
    ],
    data() {
        return {
            error: false,
            isLoading: true,
            loadDone: false,
            table: {
                columns: [
                    {
                        label: 'Customer Name',
                        field: 'name',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search For Customer by Name or Customer ID',
                        }
                    },
                    {
                        label: 'City',
                        field: 'city',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search For City',
                        }
                    },
                    {
                        label: 'Equipment',
                        field: 'system_list',
                        sortable: false,
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search For System',
                            filterDropdownItems: this.system_types,
                        }
                    },
                ],
                rows: [],
            },
            pagination: {
                links: {},
                meta:  {},
            },
            searchParam: {
                page: '',
                perPage: 25,
                sortField: 'name',
                sortType: 'asc',
                name: '',
                city: '',
                system: '',
            },
        }
    },
    mounted() {
        this.updateSearch();
    },
    methods: {
        //  Fetch new search results from the server
        updateSearch()
        {
            axios.get(this.route('customer.search', this.searchParam))
                .then(res => {
                    console.log(res.data);
                    this.pagination.links = res.data.links;
                    this.pagination.meta  = res.data.meta;
                    this.table.rows = res.data.data;
                    this.loadDone = true;
                }).catch(error => this.error = true);
        },
        //  If the user goes to the next page
        newPage(param)
        {
            this.searchParam.page = param.currentPage;
            this.searchParam.perPage = param.currentPerPage;
            this.updateSearch();
        },
        //  If the user adjusts how the tables are sorted
        reSort(param)
        {
            this.searchParam.sortField = param[0].field;
            this.searchParam.sortType  = param[0].type;
            this.updateSearch();
        },
        //  If the user adjusts how many results per page
        perPageUpdate(param)
        {
            this.searchParam.perPage = param.currentPerPage;
            this.updateSearch();
        },
        //  Filter the search paramaters
        searchFilter(data)
        {
            this.searchParam.name = data.columnFilters.name;
            this.searchParam.city = data.columnFilters.city;
            this.searchParam.system = data.columnFilters.system_list;
            this.updateSearch();
        },
        //  Reset the search back to a blank state
        resetSearch()
        {
            this.$refs['customer-list-table'].reset();
        }
    }
}
</script>

<template>
    <div class="table">
        <vue-good-table v-if="loadDone"
            mode="remote"
            ref="customer-list-table"
            styleClass="vgt-table bordered w-100"
            @on-page-change="newPage"
            @on-sort-change="reSort"
            @on-per-page-change="perPageUpdate"
            @on-column-filter="searchFilter"
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
        >
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'system_list'">
                    <div v-for="sys in data.row.customer_systems" :key="sys.cust_sys_id">{{sys.system_types.name}}</div>
                </span>
                <span v-else-if="data.column.field == 'name'" class="d-block w-100 h-100">
                    <a :href="route('customer.details', [data.row.cust_id, dashify(data.row.name)])" class="d-block w-100 h-100 text-dark">{{data.row.name}}</a>
                </span>
            </template>
            <div slot="table-actions" v-if="allow_create">
                <b-button variant="info block" :href="route('customer.id.create')"><i class="fas fa-plus" aria-hidden="true"></i> Add New Customer</b-button>
            </div>
            <template slot="loadingContent">
                <div class="text-center">Loading Customers</div>
                <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
            </template>
        </vue-good-table>
        <div v-else-if="hasError" class="card">
            <div class="card-header text-danger"><h4>Error:</h4></div>
            <div class="card-body d-flex flex-row">
            <img src="/img/err_img/sry_error.png" alt="Error Image" />
                <div class="my-auto ml-4">
                    <p>
                        Well, this is embarrassing...
                    </p>
                    <p>
                        I cannot retrieve any customers.
                    </p>
                    <p>
                        A log has been generated and our minions are hard at work to determine what went wrong.
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
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
            loadDone: false,
            isLoading: true,
            hasError: false,
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
                sysFilter: [],
            },
        }
    },
    created() {
        this.updateSearch();
    },
    methods: {
        //  Fetch new search results from the server
        updateSearch()
        {
            axios.get(this.route('customer.search', this.searchParam))
                .then(res => {
                    this.pagination.links = res.data.links;
                    this.pagination.meta  = res.data.meta;
                    this.table.rows = res.data.data;
                    this.loadDone = true;
                }).catch(error => this.hasError = true);
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
        //  Go to the customert's details page
        goToCustomer(data)
        {
            window.location.href = this.route('customer.details', [data.row.cust_id, this.dashify(data.row.name)]);
        },
        //  Filter the search paramaters
        searchFilter(data)
        {
            this.searchParam.name = data.columnFilters.name;
            this.searchParam.city = data.columnFilters.city;
            this.searchParam.system = data.columnFilters.system_list;
            this.updateSearch();
        }
    }
}
</script>




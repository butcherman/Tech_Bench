<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Customers</h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <vue-good-table
                            mode="remote"
                            :columns="table.columns"
                            :rows="table.rows"
                            :isLoading.sync="table.loading"
                            :totalRows="pagination.meta.total"
                            :pagination-options="{
                                enabled: true,
                                mode: 'records',
                                perPage: searchParam.perPage,
                                position: 'bottom',
                                perPageDropdown: [25, 50, 100, 250],
                                dropdownAllowAll: true,
                            }"
                            @on-column-filter="filterSearch"
                            @on-page-change="newPage"
                            @on-sort-change="reSort"
                            @on-per-page-change="perPageUpdate"
                        >
                            <template #table-actions>
                                <inertia-link v-if="can_create" as="b-button" variant="info" :href="route('customers.create')" pill size="sm"><i class="fas fa-plus" aria-hidden="true"></i> Add New Customer</inertia-link>
                            </template>
                            <template #emptystate>
                                <h4 v-if="!table.loading" class="text-center">No Customers Found</h4>
                            </template>
                            <template #table-row="data">
                                <span v-if="data.column.field === 'name'">
                                    <inertia-link :href="route('customers.show', data.row.slug)">{{data.row.name}}</inertia-link>
                                </span>
                            </template>
                        </vue-good-table>
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
            can_create: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                table: {
                    loading: true,
                    columns: [
                        {
                            label: 'Customer Name',
                            field: 'name',
                            filterOptions: {
                                enabled: true,
                                placeholder: 'Search for Customer by Name or Customer ID',
                            },
                        },
                        {
                            label: 'City',
                            field: 'city',
                            filterOptions: {
                                enabled: true,
                                placeholder: 'Search by City',
                            },
                        },
                        {
                            label: 'Equipment',
                            field: 'equipment',
                            filterOptions: {
                                enabled: false,
                                placeholder: 'Search by Equipment Type',
                            },
                        },
                    ],
                    rows: [],
                },
                pagination: {
                    links: {},
                    meta:  {
                        total: 0,
                    },
                },
                searchParam: {
                    page:       null,
                    perPage:    25,
                    sortField: 'name',
                    sortType:  'asc',
                    name:       null,
                    city:       null,
                    equipment:  null,
                },
            }
        },
        created() {
            //
        },
        mounted() {
             this.search();
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //  Submit Search Query
            search()
            {
                axios.post(this.route('customers.search'), this.searchParam)
                    .then(res => {
                        this.table.rows            = res.data.data;
                        this.pagination.meta.total = res.data.total;
                    });
            },
            //  Include search parameters to filter search
            filterSearch(data)
            {
                this.searchParam.page      = 1;
                this.searchParam.name      = data.columnFilters.name  ? data.columnFilters.name : null;
                this.searchParam.city      = data.columnFilters.city  ? data.columnFilters.city : null;
                this.searchParam.equipment = data.columnFilters.equip_list;
                this.search();
            },
            //  Increase or decrease the current page
            newPage(data)
            {
                this.searchParam.page = data.currentPage;
                this.search();
            },
            //  Change the order of items listed
            reSort(data)
            {
                this.searchParam.sortField = data[0].field;
                this.searchParam.sortType  = data[0].type;
                this.search();
            },
            //  Change how many rows are shown per page
            perPageUpdate(data)
            {
                this.searchParam.perPage = data.currentPerPage;
                this.search();
            }
        }
    }
</script>

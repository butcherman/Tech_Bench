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
                            <template #loadingContent>
                                <atom-loader text="Getting Customers..."></atom-loader>
                            </template>
                            <template #table-actions>
                                <inertia-link v-if="create" as="b-button" variant="info" :href="route('customers.create')" pill size="sm"><i class="fas fa-plus" aria-hidden="true"></i> Add New Customer</inertia-link>
                            </template>
                            <template #emptystate>
                                <h4 v-if="!table.loading" class="text-center">No Customers Found</h4>
                            </template>
                            <template #table-row="data">
                                <span v-if="data.column.field === 'name'">
                                    <inertia-link :href="route('customers.show', data.row.slug)">{{data.row.name}}</inertia-link>
                                </span>
                                <span v-else-if="data.column.field === 'equipment'">
                                    <div v-for="equip in data.row.customer_equipment" :key="equip.cust_equip_id">
                                        {{equip.name}}
                                    </div>
                                    <div v-for="equip in data.row.parent_equipment" :key="equip.equip_id">
                                        <i class="fas fa-share" title="Equipment Belongs to Parent Site" v-b-tooltip:hover></i>
                                        {{equip.name}}
                                    </div>
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
            /**
             * Boolean permission if the user is allowed to create a new customer or not
             */
            create: {
                type:    Boolean,
                default: false,
            },
            /**
             * Array of objects from /app/Models/EquipmentType
             */
            equip_types: {
                type:     Array,
                required: true,
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
                                enabled:      true,
                                placeholder: 'Search for Customer by Name or Customer ID',
                            },
                        },
                        {
                            label: 'City',
                            field: 'city',
                            filterOptions: {
                                enabled:      true,
                                placeholder: 'Search by City',
                            },
                        },
                        {
                            label: 'Equipment',
                            field: 'equipment',
                            filterOptions: {
                                enabled:             true,
                                placeholder:        'Search by Equipment Type',
                                filterDropdownItems: this.equip_types,
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
        mounted() {
             this.search();
        },
        methods: {
            search()
            {
                this.table.loading = true;
                axios.post(this.route('customers.search'), this.searchParam)
                    .then(res => {
                        this.table.rows            = res.data.data;
                        this.pagination.meta.total = res.data.total;
                    });
            },
            /**
             * Include search parameters to filter search
             */
            filterSearch(data)
            {
                this.searchParam.page      = 1;
                this.searchParam.name      = data.columnFilters.name  ? data.columnFilters.name : null;
                this.searchParam.city      = data.columnFilters.city  ? data.columnFilters.city : null;
                this.searchParam.equipment = data.columnFilters.equipment;
                this.search();
            },
            /**
             * Increase or decrease the current page
             */
            newPage(data)
            {
                this.searchParam.page = data.currentPage;
                this.search();
            },
            /**
             * Change the order of items listed
             */
            reSort(data)
            {
                this.searchParam.sortField = data[0].field;
                this.searchParam.sortType  = data[0].type;
                this.search();
            },
            /**
             * Change how many rows are shown per page
             */
            perPageUpdate(data)
            {
                this.searchParam.perPage = data.currentPerPage;
                this.search();
            }
        },
        metaInfo: {
            title: 'Customers',
        }
    }
</script>

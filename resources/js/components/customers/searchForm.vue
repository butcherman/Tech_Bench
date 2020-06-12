<template>
    <div class="table">
        <div v-if="error">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-4">
                    <img src="/images/errors/confused.png" alt="Error" id="header-logo" />
                </div>
                <div class="col-md-5">
                    <h3 class="text-danger">Something Bad Happened:</h3>
                    <p>
                        Sorry about that.
                    </p>
                    <p>
                        Our minions are busy at work trying to determine what happened.
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
            @on-column-filter="filterSearch"
            @on-page-change="newPage"
            @on-sort-change="reSort"
            @on-per-page-change="perPageUpdate"
        >
            <div slot="emptystate">
                <h4 v-if="!isLoading" class="text-center">No Customers Found</h4>
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

                <span v-if="data.column.field == 'name'" class="d-block w-100 h-100">
                    <a :href="route('customer.details', [data.row.cust_id, cleanURL(data.row.name)])" class="d-block w-100 h-100 text-dark">{{data.row.name}}</a>
                </span>
                <span v-else-if="data.column.field == 'equip_list'">
                    <div v-for="sys in data.row.parent_systems" :key="sys.cust_sys_id"><i class="fas fa-share-alt" title="Shared Equpipment" v-b-tooltip.hover></i> {{sys.sys_name}}</div>
                    <div v-for="sys in data.row.customer_systems" :key="sys.cust_sys_id">{{sys.sys_name}}</div>
                </span>
            </template>


        </vue-good-table>
    </div>
</template>

<script>
    export default {
        props: {
            equipment_types: {
                type:     Array,
                required: true,
            },
            allow_new: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                error:     false,
                isLoading: true,
                table: {
                    columns: [
                        {
                        label: 'Customer Name',
                        field: 'name',
                        filterOptions: {
                            enabled:      true,
                            placeholder: 'Search For Customer by Name or Customer ID',
                        }
                    },
                    {
                        label: 'City',
                        field: 'city',
                        filterOptions: {
                            enabled:      true,
                            placeholder: 'Search By City',
                        }
                    },
                    {
                        label:   'Equipment',
                        field:   'equip_list',
                        sortable: false,
                        filterOptions: {
                            enabled:             true,
                            placeholder:        'Search By Equipment',
                            filterDropdownItems: this.equipment_types,
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
            search()
            {
                this.isLoading = true;
                axios.get(this.route('customer.search', this.searchParam))
                    .then(res => {
                        console.log(res.data);
                        this.isLoading = false;
                        // this.pagination.links = res.data.links;
                        // this.pagination.meta  = res.data.meta;
                        this.pagination.meta.total = res.data.total;
                        this.table.rows = res.data.data;
                        // this.loadDone = true;
                    }).catch(error => this.error = true);
            },
            filterSearch(data)
            {
                this.searchParam.name      = data.columnFilters.name;
                this.searchParam.city      = data.columnFilters.city;
                this.searchParam.equipment = data.columnFilters.equip_list;
                this.search();
            },
            //  If the user goes to the next page
            newPage(param)
            {
                this.searchParam.page    = param.currentPage;
                this.searchParam.perPage = param.currentPerPage;
                this.search();
            },
            //  If the user adjusts how the tables are sorted
            reSort(param)
            {
                this.searchParam.sortField = param[0].field;
                this.searchParam.sortType  = param[0].type;
                this.search();
            },
            //  If the user adjusts how many results per page
            perPageUpdate(param)
            {
                this.searchParam.perPage = param.currentPerPage;
                this.search();
            },
            //  Clean the URL Name to remove any special characters
            cleanURL(str)
            {
                return encodeURI(this.dashify(str, {condense:true}));
            }
        }
    }
</script>

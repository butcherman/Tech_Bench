<template>
    <div>
        <vue-good-table
            :columns="table.columns"
            :rows="table.rows"
            styleClass="vgt-table striped bordered"
            @on-row-click="goToCustomer"
            :pagination-options="table.pagination"
        >
            <div slot="table-actions">
                <b-button variant="info" :href="new_cust_route"><i class="fa fa-plus" aria-hidden="true"></i> Add New Customer</b-button>
            </div>
        </vue-good-table>
    </div>
</template>

<script>
export default {
    props: [
        'sys_types',
        'get_cust_route',
        'new_cust_route',
    ],
    data() {
        return {
            table: {
                columns: [
                {
                    label: 'Customer Name',
                    field: 'name',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'City',
                    field: 'city',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'System Type',
                    field: 'sys',
                    html: true,
                    filterOptions: {
                        
                        enabled: true,
                        filterDropdownItems: JSON.parse(this.sys_types),
                    }
                },
                ],
                rows: [],
                pagination: {
                    enabled:          true,
                    mode:             'records',
                    perPage:          10,
                    position:         'bottom',
                    perPageDropdown:  [10, 25, 100],
                    dropdownAllowAll: true,
                }
            }
        }
    },
    created() {
        this.getCustomers();
    },
    methods: {
        getCustomers()
        {
            axios.get(this.get_cust_route)
                .then(res => {
                this.table.rows = res.data;
            })
        },
        goToCustomer(row)
        {
            location.href = row.row.url;
        }
    }
}
</script>


<style>
    .vgt-global-search__actions>div {
        margin-right: 10px;
    }
</style>
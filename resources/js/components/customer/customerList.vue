<template>
    <div>
        <vue-good-table
            :columns="table.columns"
            :rows="table.rows"
            styleClass="vgt-table striped bordered"
            @on-row-click="goToCustomer"
        ></vue-good-table>
    </div>
</template>

<script>
export default {
    props: [
        'get_cust_route',
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
                    field: 'sys'
                },
                ],
                rows: [],
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
                console.log(res.data);
            })
        },
        goToCustomer()
        {
            console.log('clicked');
        }
    }
}
</script>
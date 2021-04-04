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
                        >
                            <div slot="table-actions">
                                <inertia-link as="b-button" variant="info" :href="route('customers.create')" pill size="sm"><i class="fas fa-plus" aria-hidden="true"></i> Add New Customer</inertia-link>
                            </div>
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
            //
        },
        data() {
            return {
                //
                table: {
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
                                enabled: true,
                                placeholder: 'Search by Equipment Type',
                            },
                        },
                    ]
                }
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //
        }
    }
</script>

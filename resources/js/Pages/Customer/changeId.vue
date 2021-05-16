<template>
    <div>
        <quick-search title="Select Customer To Modify" ref="quick-search-modal" @selected-customer="goToPage"></quick-search>
    </div>
</template>

<script>
    import quickSearch from '../../Components/Customer/quickSearch.vue';
    import App         from '../../Layouts/app';

    export default {
        components: { quickSearch },
        layout: App,
        mounted() {
            this.$refs['quick-search-modal'].open();
        },
        methods: {
            goToPage(cust)
            {
                this.$inertia.get(route('customers.change-id.edit', cust.slug));
            },
        }
    }
</script>

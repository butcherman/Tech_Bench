<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-8">
                <customer-details />
            </div>
            <div class="col-md-4 col-6 mt-md-0 mt-4">
                <div class="float-md-right">
                    <edit-details />
                    <manage-customer />
                </div>
            </div>
        </div>
        <div class="row mt-2 mt-md-0">
            <div class="col-md-5 grid-margin stretch-card">
                <equipment />
            </div>
            <div class="col-md-7 grid-margin stretch-card">
                <contacts />
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <notes />
            </div>
        </div>
    </div>
</template>

<script>
    import Vue                  from 'vue';
    import upperFirst           from 'lodash/upperFirst';
    import camelCase            from 'lodash/camelCase';
    import App                  from '../../Layouts/app';
    import { useCustomerStore } from '../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    /**
     * Register all of the components for the Customer Page
     */
    const requireComponent = require.context('../../Components/Customers/Show', true, /[A-Za-z-0-9]\/[A-Za-z-0-9]\w+\.vue$/);
    requireComponent.keys().forEach(fileName => {
        const componentConfig = requireComponent(fileName);
        const componentName   = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

        Vue.component( componentName, componentConfig.default || componentConfig);
    });

    export default {
        layout: App,
        props: {
            /**
             * Collection from /app/Models/Customer
             */
            details: {
                type    : Object,
                required: true,
            },
            /**
             * Array of Collections from /app/Models/CustomerEquipment
             */
            equipment: {
                type    : Array,
                required: true,
            },
            /**
             * Array of Collections from /app/Models/CustomerContact
             */
            contacts: {
                type    : Array,
                required: true,
            },
            /**
             * Array of collections from /app/Models/CustomerNote
             */
            notes: {
                type    : Array,
                required: true,
            },
            /**
             * List of permissions that the user can and cannot access
             */
            user_data: {
                type    : Object,
                required: true,
            },
            /**
             * Notes if the customer is listed as a bookmark for the user
             */
            isFav: {
                type    : Boolean,
                required: true,
            }
        },
        data() {
            return {
                //
            }
        },
        created() {
            this.customerStore.custDetails = this.details;
            this.customerStore.equipment   = this.equipment;
            this.customerStore.contacts    = this.contacts;
            this.customerStore.notes       = this.notes;




            this.customerStore.userPerm    = this.user_data;
            this.customerStore.isFav       = this.isFav;
        },
        mounted() {
            //
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        watch: {
            details()
            {
                this.customerStore.custDetails = this.details;
            },
            equipment()
            {
                this.customerStore.equipment = this.equipment;
            },
            contacts()
            {
                this.customerStore.contacts = this.contacts;
            },
            notes()
            {
                this.customerStore.notes = this.notes;
            }
        },
        methods: {
            //
        },
    }
</script>

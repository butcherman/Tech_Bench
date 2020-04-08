<template>
    <b-modal id="customer_search_modal" :title="title" ref="selectCustomerModal" scrollable @cancel="cancelSelectCustomer" size="lg">
        <b-form @submit="searchCustomer">
            <b-input-group>
                <b-form-input type="text" v-model="searchParam.name" placeholder="Enter Customer Name or ID Number"></b-form-input>
                <b-input-group-append>
                    <b-button varient="outline-secondary" @click="searchCustomer"><span class="fas fa-search"></span></b-button>
                </b-input-group-append>
            </b-input-group>
        </b-form>
        <atom-spinner v-if="loading"
            :animation-duration="1000"
            :size="60"
            color="#ff1d5e"
            class="mx-auto"
        />
        <div v-else>
            <div id="search-results" class="mt-4" v-if="searchResults.length > 0">
                <h4 class="text-center">Select A Customer</h4>
                <b-list-group>
                    <b-list-group-item v-for="res in searchResults" v-bind:key="res.cust_id" class="pointer" @click="selectCustomer(res)">{{res.name}}</b-list-group-item>
                    <b-list-group-item>
                        <div class="text-muted float-left w-auto">Showing items {{searchMeta.from}} to {{searchMeta.to}} of {{searchMeta.total}}</div>
                        <div class="text-muted float-right w-auto">
                            <span class="pointer" v-if="searchMeta.current_page != 1" @click="updatePage(searchMeta.current_page - 1)">
                                <span class="fas fa-angle-double-left"></span> Previous
                            </span>
                            -
                            <span class="pointer" v-if="searchMeta.current_page != searchMeta.last_page" @click="updatePage(searchMeta.current_page + 1)">
                                Next <span class="fas fa-angle-double-right"></span>
                            </span>
                        </div>
                    </b-list-group-item>
                </b-list-group>
            </div>
        </div>
    </b-modal>
</template>

<script>
    export default {
        props: {
            show_form: {
                type: Boolean,
                default: false,
                required: false,
            },
            title: {
                type: String,
                default: 'Search For Customer',
                required: false,
            }
        },
        data: function () {
            return {
                loading: false,
                searchParam: {
                    name: '',
                    page: '',
                    perPage: 25,
                    sortField: 'name',
                    sortType: 'asc',
                },
                searchResults: [],
                searchMeta: [],
            }
        },
        mounted() {
            if(this.show_form)
            {
                this.$bvModal.show('customer_search_modal');
            }
        },
        methods: {
            //  Perform a search for a matching customer
            searchCustomer(e)
            {
                if(e)
                {
                    e.preventDefault();
                    this.searchParam.page = '';
                }
                //  Submit the search form
                this.loading = true;
                axios.get(this.route('customer.search', this.searchParam))
                    .then(res => {
                        this.searchResults = res.data.data;
                        this.searchMeta = res.data.meta;
                        this.loading = false;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            //  Move to a page number
            updatePage(newPage)
            {
                this.searchParam.page = newPage;
                this.searchCustomer();
            },
            //  Attach the customer
            selectCustomer(cust)
            {
                this.$bvModal.hide('customer_search_modal');
                this.$emit('selectedCust', cust);
                this.reset();
            },
            //  Cancel the current operation
            cancelSelectCustomer()
            {
                this.$bvModal.hide('customer_search_modal');
                this.reset();
                this.$emit('selectCanceled');
            },
            //  Reset the default values
            reset()
            {
                this.loading = false,
                this.searchParam.name = '';
                this.searchParam.page = '';
                this.searchResults = [];
                this.searchMeta =  [];
            },
        },
        watch: {
            //  Monitor the show_form to see if Modal should be up or not
            show_form()
            {
                this.show_form ? this.$bvModal.show('customer_search_modal') : this.$bvModal.hide('customer_search_modal');
            }
        }
    }
</script>

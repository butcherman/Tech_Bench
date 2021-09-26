<template>
    <b-overlay :show="loading">
        <template #overlay>
            <atom-loader text="Loading Data..."></atom-loader>
        </template>
        <h4 class="mb-4">
            Select Customer to Continue
        </h4>
        <ValidationObserver v-slot="{handleSubmit}">
            <b-form @submit.prevent="handleSubmit(search)" novalidate>
                <b-form-row>
                    <b-col md="10">
                        <b-input v-model="searchParam.name" name="name" placeholder="Search Customer Name or ID Number"></b-input>
                    </b-col>
                    <b-col md="2">
                        <submit-button button_text="Search" :submitted="loading" class="mt-auto"></submit-button>
                    </b-col>
                </b-form-row>
            </b-form>
        </ValidationObserver>
        <div>
            <b-list-group>
                <b-list-group-item
                    v-for="(cust, index) in results"
                    :key="index"
                    @click="selectCustomer(cust)"
                    class="pointer"
                >{{cust.name}}</b-list-group-item>
                <b-list-group-item v-if="results.length > 0">
                    <b-row class="text-muted">
                        <b-col class="text-left">
                            <span class="pointer" v-if="meta.previous" @click="searchParam.page--; search()">
                                <span class="fas fa-angle-double-left"></span>
                                Previous
                            </span>
                        </b-col>
                        <b-col class="text-center">
                            Showing items {{meta.from}} to {{meta.to}} of {{meta.total}}
                        </b-col>
                        <b-col class="text-right">
                            <span class="pointer" v-if="meta.next" @click="searchParam.page++; search()">
                                Next
                                <span class="fas fa-angle-double-right"></span>
                            </span>
                        </b-col>
                    </b-row>
                </b-list-group-item>
            </b-list-group>
        </div>
    </b-overlay>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        data() {
            return {
                loading:   false,
                showModal: false,
                searchParam: {
                    page:       null,
                    perPage:    10,
                    sortField: 'name',
                    sortType:  'asc',
                    name:       null,
                },
                results: [],
                meta: {
                    from:     null,
                    to:       null,
                    total:    null,
                    previous: null,
                    next:     null,
                }
            }
        },
        methods: {
            //  Search for the customer
            search()
            {
                this.loading = true;
                axios.post(this.route('customers.search'), this.searchParam)
                    .then(res => {
                        this.searchParam.page = res.data.current_page;
                        this.results          = res.data.data;
                        this.meta.from        = res.data.from;
                        this.meta.to          = res.data.to;
                        this.meta.total       = res.data.total;
                        this.meta.previous    = res.data.prev_page_url;
                        this.meta.next        = res.data.next_page_url;

                        this.loading = false;
                    });
            },
            //  When a customer is selected, close modal and emit that customer as an event
            selectCustomer(cust)
            {
                this.$inertia.get(route('admin.cust.change-id.edit', cust.slug));
            }
        }
    }
</script>

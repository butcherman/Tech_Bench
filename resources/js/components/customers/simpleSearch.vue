<template>
    <b-modal
        :title="title"
        :visible="visible"
        ref="customer-search-modal"
        size="lg"
        scrollable
        hide-footer
        @close="closeForm"
        @cancel="closeForm"
        @hide="closeForm"
    >
        <b-overlay :show="loading">
            <template v-slot:overlay>
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Processing</h4>
            </template>
            <b-form @submit="search">
                <b-input-group>
                    <b-form-input type="text" v-model="form.name" @change="form.page = 1" placeholder="Enter Customer Name or ID Number"></b-form-input>
                    <b-input-group-append>
                        <b-button varient="outline-secondary" @click="search"><span class="fas fa-search"></span></b-button>
                    </b-input-group-append>
                </b-input-group>
            </b-form>
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
        </b-overlay>
    </b-modal>
</template>

<script>
    export default {
        props: {
            title: {
                type:     String,
                required: false,
                default: 'Search For Customer',
            },
        },
        data() {
            return {
                loading: false,
                visible: false,
                form: {
                    name:      null,
                    page:      '',
                    perPage:    25,
                    sortField: 'name',
                    sortType:  'asc',
                },
                searchResults: [],
                searchMeta:    {},
            }
        },
        methods: {
            search(e)
            {
                e.preventDefault();
                this.loading = true;
                axios.get(this.route('customer.search', this.form))
                    .then(res => {
                        this.searchResults = res.data.data;
                        this.searchMeta = {
                            current_page: res.data.current_page,
                            last_page:    res.data.last_page,
                            from:         res.data.from,
                            to:           res.data.to,
                            total:        res.data.total,
                        };
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            updatePage(page)
            {
                this.form.page = page;
                this.search();
            },
            openForm(str = null)
            {
                this.visible   = true;
                this.form.name = str;
                if(str != null)
                {
                    this.search();
                }
            },
            closeForm()
            {
                this.visible = false;
                this.form = {
                    name:      null,
                    page:      '',
                    perPage:    25,
                    sortField: 'name',
                    sortType:  'asc',
                };
                this.searchResults = [];
                this.searchMeta    = {};
                this.$emit('form-closed');
            },
            selectCustomer(data)
            {
                this.$emit('selected-customer', data);
                this.closeForm();
            }
        }
    }
</script>

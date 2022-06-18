<template>
    <div>
        <h3>
            <i v-if="loadingFav" class="fas fa-spinner fa-spin" />
            <i
                v-else
                class="fa-bookmark"
                :class="bookmark_class"
                :title="bookmark_title"
                v-b-tooltip.hover
                @click="toggleFav"
            />
            {{customerStore.custDetails.name}}
            <small>
                <i
                    v-if="customerStore.custDetails.child_count > 0"
                    class="fas fa-link pointer text-secondary"
                    title="Show Linked Customers"
                    v-b-tooltip.hover
                    v-b-modal.linked-customers-modal
                ></i>
            </small>
        </h3>
        <h5 v-if="customerStore.custDetails.dba_name">
            AKA - {{customerStore.custDetails.dba_name}}
        </h5>
        <h5 v-if="customerStore.custDetails.parent_id">
            Child Site of -
            <inertia-link
                :href="route('customers.show', customerStore.custDetails.parent.slug)"
            >
                {{customerStore.custDetails.parent.name}}
            </inertia-link>
        </h5>
        <address>
            <div class="float-left">
                <i class="fas fa-map-marked-alt text-muted"></i>
            </div>
            <a
                id="addr-span"
                :href="map_url"
                target="_blank"
                class="float-left ml-2"
                title="Click for Google Maps"
                v-b-tooltip.hover
            >
                {{customerStore.custDetails.address}}<br />
                {{customerStore.custDetails.city}},
                {{customerStore.custDetails.state}}
                &nbsp;{{customerStore.custDetails.zip}}
            </a>
        </address>
        <b-modal
            id="linked-customers-modal"
            :title="`Customers linked to ${customerStore.custDetails.name}`"
            ok-only
            @show="getLinkedCustomers"
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader />
                </template>
                <b-list-group>
                    <b-list-group-item
                        v-for="l in linked"
                        :key="l.cust_id"
                        class="text-center"
                    >
                        <inertia-link :href="route('customers.show', l.slug)">
                            {{l.name}}
                        </inertia-link>
                    </b-list-group-item>
                </b-list-group>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                loading   : false,
                loadingFav: false,
                linked    : [],
            }
        },
        computed: {
            map_url()
            {
                let mapURI = encodeURI(
                   `${this.customerStore.custDetails.address},
                    ${this.customerStore.custDetails.city},
                    ${this.customerStore.custDetails.state}`
                );
                return `https://maps.google.com/?${mapURI}`;
            },
            bookmark_class()
            {
                return this.customerStore.isFav ? 'fas bookmark-checked' : 'far bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.customerStore.isFav ? 'Remove From Bookmarks' : 'Add to Bookmarks'
            },
            ...mapStores(useCustomerStore),
        },
        methods: {
            /**
             * Ajax call to enable/disable the customer as a user bookmark
             */
            toggleFav()
            {
                this.loadingFav = true;
                var data = {
                    cust_id: this.customerStore.custDetails.cust_id,
                    state  : !this.customerStore.isFav,
                }

                axios.post(route('customers.bookmark'), data)
                    .then(() => {
                        this.customerStore.isFav = !this.customerStore.isFav;
                        this.loadingFav          = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            /**
             * If this customer is linked to other customers, get the list of linked customers
             */
            getLinkedCustomers()
            {
                if(this.linked.length == 0)
                {
                    this.loading = true;
                    axios.get(route('customers.get-linked', this.customerStore.custDetails.cust_id))
                        .then(res => {
                            this.linked  = res.data;
                            this.loading = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
        },
    }
</script>

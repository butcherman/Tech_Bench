<template>
    <div>
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <h3>
                    <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
                    {{details.name}}
                </h3>
                <h5 v-if="details.dba_name">AKA - {{details.dba_name}}</h5>
                <h5 v-if="details.parent_id">Child Site of - <inertia-link :href="route('customers.show', details.parent.slug)">{{details.parent.name}}</inertia-link></h5>
                <address>
                    <div class="float-left">
                        <i class="fas fa-map-marked-alt text-muted"></i>
                    </div>
                    <a :href="map_url" target="_blank" id="addr-span" class="float-left ml-2" title="Click for Google Maps" v-b-tooltip.hover>
                        {{details.address}}<br />
                        {{details.city}}, {{details.state}} &nbsp;{{details.zip}}
                    </a>
                </address>
            </div>
            <div class="col-md-4 mt-md-0 mt-4">
                <div class="float-md-right">
                    <edit-details :details="details"></edit-details>
                    <manage-customer
                        v-if="user_data.manage"
                        :cust_id="details.cust_id"
                        :can_deactivate="user_data.deactivate"
                        :linked="details.parent_id > 0 ? true : false"
                        :is_parent="details.child_count > 0 ? true : false"
                    ></manage-customer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';
    import editDetails from '../../Components/Customers/editDetails.vue';
    import ManageCustomer from '../../Components/Customers/manageCustomer.vue';

    export default {
        components: { editDetails, ManageCustomer },
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            },
            user_data: {
                type:    Object,
                required: true,
            }
        },
        data() {
            return {
                is_fav: this.user_data.fav,
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
            map_url()
            {
                return 'https://maps.google.com/?q='+encodeURI(this.details.address+','+this.details.city+','+this.details.state);
            },
            bookmark_class()
            {
                return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks'
            }
        },
        watch: {
             //
        },
        methods: {
            toggleFav()
            {
                var form = {
                    cust_id: this.details.cust_id,
                    state:   !this.is_fav,
                }

                axios.post(this.route('customers.bookmark'), form)
                    .then(res => {
                        console.log(res);
                        this.is_fav = !this.is_fav;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
        },
        metaInfo: {
            title: 'Customer Details',
        }
    }
</script>

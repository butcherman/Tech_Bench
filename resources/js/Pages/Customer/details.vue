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
                    <edit-details v-if="user_functions.edit" :details="details"></edit-details>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <customer-equipment :customer_equipment="details.customer_equipment" :cust_id="details.cust_id"></customer-equipment>
                    </div>
                </div>
            </div>
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <customer-contacts :cust_id="details.cust_id" :customer_contacts="details.customer_contact"></customer-contacts>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <customer-notes :cust_id="details.cust_id" :customer_notes="details.customer_note"></customer-notes>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App               from '../../Layouts/app';
    import editDetails       from '../../Components/Customer/editDetails.vue';
    import CustomerContacts  from '../../Components/Customer/customerContacts.vue';
    import CustomerEquipment from '../../Components/Customer/customerEquipment.vue';
    import CustomerNotes     from '../../Components/Customer/customerNotes.vue';

    export default {
        components: { editDetails, CustomerEquipment, CustomerContacts, CustomerNotes },
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            },
            user_functions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                is_fav: this.user_functions.fav,
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
                axios.put(this.route('customers.bookmark'), {cust_id: this.details.cust_id, state: !this.is_fav})
                    .then(this.is_fav = !this.is_fav);
            },
        }
    }
</script>

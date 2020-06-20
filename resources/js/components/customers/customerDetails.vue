<template>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <h3>
                <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
                {{details.name}}
            </h3>
            <h5 v-if="details.dba_name">AKA - {{details.dba_name}}</h5>
            <h5 v-if="details.parent_id">Child Site of - <a :href="route('customer.details', [parent.cust_id, dashify(parent.name)])" target="_blank">{{parent.name}}</a></h5>
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
                <edit-cust-details :details="customer_details" :has_parent="details.parent_id" @updated="resetDetails" class="mb-2"></edit-cust-details>
                <b-button v-show="allow_deactivate" class="btn btn-danger btn-block" pill size="sm" @click="deactivateCust">Deactivate Customer</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            customer_details: {
                type:     Object,
                required: true,
            },
            parent_details: {
                type:     Object,
                required: false,
                default:  null,
            },
            can_deactivate: {
                type:     Boolean,
                required: false,
                default:  false,
            },
            is_fav: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                fav: this.is_fav,
                details: this.customer_details,
                parent: this.parent_details,
            }
        },
        mounted() {
             this.eventHub.$on('parent-linked', data => {
                 this.parentLinked(data);
             });
        },
        computed: {
            bookmark_class()
            {
                return this.fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.fav ? 'Remove from Favorites' : 'Add to Favorites';
            },
            map_url()
            {
                return 'https://maps.google.com/?q='+encodeURI(this.details.address+','+this.details.city+','+this.details.state);
            },
            allow_deactivate()
            {
                if(!this.details.parent_id && this.details.child_count == 0 && this.can_deactivate)
                {
                    return true;
                }

                return false;
            },
        },
        methods: {
            toggleFav()
            {
                axios.get(this.route('customer.toggle_fav', this.details.cust_id))
                    .then(res => {
                        this.fav = res.data.favorite;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deactivateCust()
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+this.details.name, {
                    title:          'Please Confirm',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                })
                .then(value => {
                    if(value)
                    {
                        axios.delete(this.route('customer.destroy', this.details.cust_id))
                            .then(res => {
                                location.href = this.route('customer.index');
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                })
                .catch(error => {
                    this.eventHub.$emit('axiosError', error);
                });
            },
            resetDetails(details)
            {
                this.details.name     = details.name;
                this.details.dba_name = details.dba_name;
                this.details.address  = details.address;
                this.details.city     = details.city;
                this.details.state    = details.state;
                this.details.zip      = details.zip;
            },
            parentLinked(data)
            {
                if(!data)
                {
                    this.details.parent_id = null;
                }
                else
                {
                    this.details.parent_id = data.cust_id;
                    this.parent = data;
                }
            }
        }
    }
</script>

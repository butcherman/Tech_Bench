<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Deactivated Customers</h4>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-body">
                         <b-overlay :show="loading">
                            <template #overlay>
                                <form-loader></form-loader>
                            </template>
                            <b-table
                                striped
                                responsive
                                :items="list"
                                :fields="fields"
                                selectable
                                select-mode="multi"
                                @row-selected="updateSelected"
                            >
                                <template #cell(selected)="{ rowSelected }">
                                    <template v-if="rowSelected">
                                        <span aria-hidden="true">&check;</span>
                                    </template>
                                </template>
                            </b-table>
                            <div v-if="selected.length > 0" class="text-center">
                                <b-button variant="success" @click="restore">Restore Selected Customers</b-button>
                                <b-button variant="danger" @click="deleteCust">Delete Selected Customers</b-button>
                            </div>
                         </b-overlay>
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
            list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                selected: [],
                fields: [
                    {
                        key:     'selected',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'cust_id',
                        label:   'ID',
                        sortable: true,
                    },
                    {
                        key:     'name',
                        label:   'Name',
                        sortable: true,
                    },
                    {
                        key:     'city',
                        label:   'City',
                        sortable: true,
                    },
                    {
                        key:     'deleted_at',
                        label:   'Deactivated Date',
                        sortable: true,
                    },
                ],
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
            updateSelected(items)
            {
                this.selected = items;
            },
            restore()
            {
                var processed = 0;
                this.loading  = true;

                this.selected.forEach(item => {
                    axios.get(route('customers.restore', item.cust_id)).then(() => {
                        processed++;
                        if(processed === this.selected.length)
                        {
                            this.$inertia.get(route('customers.show-deactivated'));
                        }
                    }).catch(error => this.eventHub.$emit('axiosError', error));
                });
            },
            deleteCust()
            {
                this.$bvModal.msgBoxConfirm('This is permanent and cannot be undone',
                    {
                        title:          'Are you sure?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            var processed = 0;
                            this.loading  = true;
                            this.selected.forEach(item => {
                                axios.delete(this.route('customers.force-delete', item.cust_id))
                                    .then(res => {
                                        processed++;
                                        if(processed === this.selected.length)
                                        {
                                            this.$inertia.get(route('customers.show-deactivated'));
                                        }
                                    }).catch(error => this.eventHub.$emit('axiosError', error));
                            });
                        }
                    });
            }
        }
    }
</script>

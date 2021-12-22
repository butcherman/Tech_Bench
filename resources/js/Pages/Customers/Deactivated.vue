<template>
    <div>
        <div class="row">
            <div class="col grid-margin">
                <h4>
                    Deactivated Customers
                </h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin">
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
                loading:  false,
                selected: [],
                fields:   [
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
        methods: {
            updateSelected(items)
            {
                this.selected = items;
            },
            restore()
            {
                this.loading  = true;
                this.$inertia.post(route('customers.restore'), {list: this.selected}, {
                    onFinish: () => {
                        this.loading = false;
                    }
                });
            },
            deleteCust()
            {
                this.$bvModal.msgBoxConfirm('All Customer Data and Files will be deleted',
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
                            this.loading = true;
                            this.$inertia.post(route('customers.force-delete'), {list: this.selected}, {
                                onFinish: () =>
                                {
                                    this.loading = false;
                                }
                            });
                        }
                    });
            }
        },
        metaInfo: {
            title: 'Deactivated Customers',
        }
    }
</script>

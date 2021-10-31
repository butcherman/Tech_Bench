<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Disabled Users</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <b-table striped :items="user_list" :fields="table.fields">
                            <template #cell(actions)="data">
                                <i class="fas fa-unlock-alt pointer" title="Enable User" v-b-tooltip.hover @click="enableUser(data.item)"></i>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            user_list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                table: {
                    fields: [
                        {
                            key:     'username',
                            label:   'Username',
                            sortable: true,
                        },
                        {
                            key:     'full_name',
                            label:   'Name',
                            sortable: true,
                        },
                        {
                            key:     'email',
                            label:   'email',
                            sortable: true,
                        },
                        {
                            key:     'deleted_at',
                            label:   'Disabled Date',
                            sortable: true,
                        },
                        {
                            key:     'actions',
                            label:   'Actions',
                            sortable: false,
                        },
                    ]
                }
            }
        },
        methods: {
            enableUser(user)
            {
                this.$inertia.get(route('admin.reactivate-user', user.username), {
                    onFinish: () => {
                        console.log('done');
                    }
                });
            }
        },
        metaInfo: {
            title: 'Deactivated Users',
        }
    }
</script>

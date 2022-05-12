<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">User Roles and Permissions</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Select Role to Modify</div>
                        <vue-good-table
                            :columns="fields"
                            :rows="roles"
                            row-style-class="pointer"
                            @on-row-click="editRow"
                        >
                            <template #table-actions-bottom>
                                <div class="text-center m-3">
                                    <b-button class="ml-auto" variant="success" v-b-modal.create-role-modal>Create New Role</b-button>
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="create-role-modal" title="Select Baseline Role to Copy Permissions From">
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader></atom-loader>
                </template>
                <b-list-group>
                    <b-list-group-item><inertia-link as="b-button" :href="route('admin.user-roles.create')" block variant="info">None (All Options Off)</inertia-link></b-list-group-item>
                    <b-list-group-item v-for="role in roles" :key="role.role_id"><inertia-link as="b-button" :href="route('admin.user-roles.create', role.name)" block variant="info">{{role.name}}</inertia-link></b-list-group-item>
                </b-list-group>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Array of Objects from /app/Models/UserRoles
             */
            roles: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                fields: [
                    {
                        field: 'name',
                        label: 'Name',
                    },
                    {
                        field: 'description',
                        label: 'Description',
                    }
                ],
            }
        },
        methods: {
            editRow(e)
            {
                this.$inertia.get(route('admin.user-roles.edit', e.row.role_id));
            }
        },
        metaInfo: {
            title: 'User Roles',
        }
    }
</script>

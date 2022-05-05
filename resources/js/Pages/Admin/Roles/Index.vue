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
                                    <inertia-link as="b-button" :href="route('admin.user-roles.create')" class="ml-auto" variant="success">Create New Role</inertia-link>
                                </div>
                            </template>
                        </vue-good-table>
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

<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">User Permissions Report</h4>
            </div>
        </div>
        <div class="row">
            <div class="col grid-margin">
                <div class="card">
                    <div class="card-body">
                        <b-list-group>
                            <b-list-group-item v-for="user in data" :key="user.email">
                                <h4>{{user.full_name}}</h4>
                                <h5>Role:  {{user.user_roles.description}}</h5>
                                <h5>Permissions:</h5>
                                <div class="row mx-4" v-for="(group, name) in permissions" :key="name">
                                    <p class="w-100">{{name.length ? name : 'Misc'}}</p>
                                    <div class="col-6 col-lg-4" v-for="opt in group" :key="opt.perm_type_id">
                                        <i class="fas" :class="getPermAllow(opt, user.user_roles.user_role_permissions)"></i>
                                        {{opt.description}}
                                    </div>
                                </div>
                            </b-list-group-item>
                        </b-list-group>
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
             * Report data - collection from /app/Models/User with all role permissions
             */
            data: {
                type:     Array,
                required: true,
            },
            /**
             * Object of collections from /app/Models/UserRolePermissionTypes
             */
            permissions: {
                type:     Object,
                required: true,
            }
        },
        methods: {
            //  Determine if a check or x should be shown based on the permissions of the user
            getPermAllow(opt, perm)
            {
                var allow = perm.filter(e => e.perm_type_id === opt.perm_type_id);

                return allow[0].allow ? 'fa-check text-success' : 'fa-times text-danger';
            }
        }
    }
</script>

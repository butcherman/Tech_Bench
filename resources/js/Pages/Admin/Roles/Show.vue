<template>
    <Head title="View Role" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Role Data</div>
                        <h5 class="text-center">{{ role.name }}</h5>
                        <p class="text-center">{{ role.description }}</p>
                        <div class="border-top">
                            <div
                                v-for="(group, name) in permissions"
                                :key="name"
                                class="row"
                            >
                                <h6>{{ name }}</h6>
                                <div
                                    class="col-6 col-lg-4"
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                >
                                    <fa-icon
                                        :icon="opt.allow ? 'fa-check' : 'fa-xmark'"
                                        :class="opt.allow ? 'text-success' : 'text-danger'"
                                    />
                                    {{ opt.user_role_permission_types.description }}
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="$route('admin.users.roles.edit', role.role_id)"
                                type="button"
                                class="btn btn-primary w-50 m-1"
                                :disabled="!role.allow_edit"
                            >
                                {{ !role.allow_edit ? 'Cannot Edit A Default Role' : 'Edit Role' }}
                            </Link>
                        </div>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="$route('admin.users.roles.copy', role.role_id)"
                                type="button"
                                class="btn btn-primary w-50 m-1"
                            >
                                Copy Role
                            </Link>
                        </div>
                        <div class="text-center">
                            <button
                                type="button"
                                class="btn btn-danger w-50 m-1"
                                :disabled="!role.allow_edit"
                                @click="verifyDelete"
                            >
                            {{ !role.allow_edit ? 'Cannot Delete A Default Role' : 'Delete Role' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                             from '@/Layouts/app.vue';
    import { verifyModal }                 from '@/Modules/verifyModal.module';
    import { router }                     from '@inertiajs/vue3';
    import type { userRolePermissionsType,
                  userRoleType }           from '@/Types';

    const $route = route;
    const props  = defineProps<{
        role       : userRoleType;
        permissions: {
            [key:string]:userRolePermissionsType[];
        }
    }>();

    const verifyDelete = () => {
        verifyModal('This cannot be undone').then(res => {
            if(res)
            {
                router.delete(route('admin.users.roles.destroy', props.role.role_id));
            }
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>

<template>
    <InitLayout :step-id="5">
        <div class="card-title">Finish Setup</div>
        <p class="text-center">
            Setup is completed.  Use one of the navigation options below to setup
            Users and Equipment, or use the navigation
        </p>
        <div class="row justify-content-center">
            <div class="col-6">
                <Link :href="$route('admin.users.index')" class="btn btn-primary w-100 my-2">
                    User Administration
                </Link>
                <Link :href="$route('equipment.index')" class="btn btn-primary w-100 my-2">
                    Equipment Administration
                </Link>
                <Link :href="$route('admin.index')" class="btn btn-primary w-100 my-2">
                    Administration Page
                </Link>
            </div>
        </div>
    </InitLayout>
</template>

<script setup lang="ts">
    import InitLayout from './InitLayout.vue';

    const $route = route;
</script>

<template>
    <Guest>
        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col">
                    <h1 class="text-center text-light">{{appName}}</h1>
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center align-items-center">
                                <div class="col col-12 col-md-6 text-center">
                                    <img :src="appLogo" alt="Company Logo" id="header-logo" class="img-fluid" />
                                </div>
                                <div class="col col-12 col-md-6">
                                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger text-center">
                                        <div v-for="error in errors">
                                            {{ error }}
                                        </div>
                                    </div>
                                    <div v-if="warning" class="alert alert-warning text-center">
                                        {{ warning }}
                                    </div>
                                    <div v-if="success" class="alert alert-success text-center">
                                        {{ success }}
                                    </div>
                                    <slot />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Guest>
</template>

<script setup lang="ts">
    import Guest        from './guest.vue';
    import { computed } from 'vue';
    import { usePage }  from '@inertiajs/inertia-vue3';

    import type { pageInterface } from '@/Types';

    const appName = computed(() => usePage<pageInterface>().props.value.app.name);
    const appLogo = computed(() => usePage<pageInterface>().props.value.app.logo);
    const errors  = computed(() => usePage<pageInterface>().props.value.errors);
    const warning = computed(() => usePage<pageInterface>().props.value.flash.warning);
    const success = computed(() => usePage<pageInterface>().props.value.flash.success);
</script>

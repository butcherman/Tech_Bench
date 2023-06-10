<template>
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col">
                <h1 class="text-center text-light">{{ app.name }}</h1>
                <div class="card">
                    <div class="card-body">
                        <div
                            class="row justify-content-center align-items-center"
                        >
                            <div class="col col-12 col-md-6">
                                <img
                                    :src="app.logo"
                                    alt="Company Logo"
                                    id="header-logo"
                                    class="img-fluid"
                                />
                            </div>
                            <div class="col col-12 col-md-6">
                                <div
                                    v-if="Object.keys(errors).length"
                                    class="alert alert-danger text-center"
                                >
                                    <div v-for="error in errors">
                                        {{ error }}
                                    </div>
                                </div>
                                <div
                                    v-if="flash.warning"
                                    class="alert alert-warning text-center"
                                >
                                    {{ flash.warning }}
                                </div>
                                <div
                                    v-if="flash.success"
                                    class="alert alert-success text-center"
                                >
                                    {{ flash.success }}
                                </div>
                                <div
                                    v-if="flash.status"
                                    class="alert alert-success text-center"
                                >
                                    {{ flash.status }}
                                </div>
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    app: appProps;
    errors: errors;
    flash: flashProps;
}>();
</script>

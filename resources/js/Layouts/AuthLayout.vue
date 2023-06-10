<template>
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col">
                <h1 class="text-center text-light">{{ app.name }}</h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-12 col-md-6">
                                <img :src="app.logo" alt="Company Logo" id="header-logo" class="img-fluid" />
                            </div>
                            <div class="col col-12 col-md-6">
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
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps<{
    app: appProps;
}>();
</script>

<script lang="ts">
export default { layout: GuestLayout };
</script>

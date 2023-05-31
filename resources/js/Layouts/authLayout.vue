<template>
    <Guest>
        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col">
                    <h1 class="text-center text-light">{{ app.name }}</h1>
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="row justify-content-center align-items-center"
                            >
                                <div class="col col-12 col-md-6 text-center">
                                    <img
                                        :src="app.logo"
                                        alt="Company Logo"
                                        id="header-logo"
                                        class="img-fluid"
                                    />
                                </div>
                                <div class="col col-12 col-md-6">
                                    <div
                                        v-if="Object.keys(errors).length > 0"
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
import Guest from "./guest.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page: pageData = usePage();
const app = computed(() => page.props.app);
const errors = computed(() => page.props.errors);
const flash = computed(() => page.props.flash);
</script>

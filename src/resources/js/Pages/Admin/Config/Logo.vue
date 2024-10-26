<template>
    <div>
        <Head title="Tech Bench Logo" />
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Logo</div>
                        <img
                            id="app-logo-current"
                            :src="logo"
                            alt="Tech Bench Logo"
                            class="img-fluid"
                        />
                    </div>
                </div>
            </div>
            <div v-if="showForm" class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">New Logo</div>
                        <LogoForm @success="showForm = false" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import LogoForm from "@/Forms/Admin/Config/LogoForm.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const logo = computed(() => usePage<pageProps>().props.app.logo);
const showForm = ref(true);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <Head title="App Logo" />
    <Overlay :loading="loading">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Logo</div>
                        <img
                            id="app-current-logo"
                            :src="logo"
                            :key="logo"
                            alt="Tech Bench Logo"
                            class="img-fluid"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="card-title">New Logo</div>
                        <LogoForm 
                            class="d-flex flex-column flex-grow-1" 
                            @completed="refresh"  
                        />
                    </div>
                </div>
            </div>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import LogoForm from "@/Forms/Admin/Config/LogoForm.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps<{
    logo: string;
}>();

const loading = ref(false);
const refresh = () => {
    loading.value = true;
    router.reload({ onFinish: () => (loading.value = false) });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

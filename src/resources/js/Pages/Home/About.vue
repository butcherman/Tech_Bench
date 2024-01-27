<template>
    <div>
        <Head title="About" />
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <img
                            src="/images/TechBenchLogo.png"
                            class="mx-auto d-block img-fluid"
                            alt="Tech Bench"
                        />
                        <p class="text-center">
                            Tech Bench &copy; {{ app.copyright }}
                            <span class="d-inline-block">
                                Butcherman - All Rights Reserved
                            </span>
                        </p>
                        <p class="text-center">
                            {{ app.version }} (Build {{ build }})
                        </p>
                        <p class="text-center">Build Date - {{ build_date }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { useAppStore } from "@/Store/AppStore";

defineProps<{
    build: string;
    build_date: string;
}>();
const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

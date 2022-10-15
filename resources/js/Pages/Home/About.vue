<template>
    <Head title="About" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <img src="/images/TechBenchLogo.png" class="mx-auto d-block img-fluid" alt="Tech Bench"/>
                        <p class="text-center">
                            Tech Bench &copy; {{ copyright }}
                            <span class="d-inline-block">Butcherman - All Rights Reserved</span>
                        </p>
                        <p class="text-center">{{ version }} (Build {{build}})</p>
                        <p class="text-center">Build Date - {{ build_date }}</p>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';

    defineProps<{
        version   : string;
        copyright : string;
        build     : string;
        build_date: string;
    }>();
</script>

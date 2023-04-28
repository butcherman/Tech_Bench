<template>
    <Head title="First Time Setup" />
    <App>
        <div class="init-wrapper">
            <h3 class="text-center">First Time Setup</h3>
            <StepNavigation :step-list="stepList" :current-step="stepId" />
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body" id="setup-card">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import { ref, reactive, onMounted } from 'vue';
    import StepNavigation from '@/Components/Base/StepNavigation.vue';

    const props = defineProps<{
        stepId: number;
    }>();
    const stepList = ref([
        {
            id    : 1,
            name  : 'Welcome',
            icon  : 'fa-house',
            active: props.stepId === 1 ? true : false,
        },
        {
            id: 2,
            name: 'Basic Settings',
            icon: 'fa-cog',
            active: props.stepId === 2 ? true : false,
        },
        {
            id: 3,
            name: 'Email Settings',
            icon: 'fa-envelope',
            active: props.stepId === 3 ? true : false,
        },
        {
            id: 4,
            name: 'User Settings',
            icon: 'fa-users-cog',
            active: props.stepId === 4 ? true : false,
        }
    ]);
</script>

<style lang="scss">
    #setup-card {
        min-height: 50vh;
    }
</style>

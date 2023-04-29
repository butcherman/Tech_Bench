<template>
    <InitLayout :step-id="4">
        <div class="card-title">User Settings</div>
    </InitLayout>
</template>

<script setup lang="ts">
    // import App from '@/Layouts/app.vue';
    import { ref, reactive, onMounted } from 'vue';
    // import { Layout } from './Layout.vue';
    import InitLayout from './InitLayout.vue';
    // import StepNavigation from '@/Components/Base/StepNavigation.vue';

    const $route = route;
    const props = defineProps<{}>();
</script>

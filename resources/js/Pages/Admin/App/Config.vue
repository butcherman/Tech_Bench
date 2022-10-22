<template>
    <App>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">This is a new page</p>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import { ref, reactive, onMounted } from 'vue';

    const props = defineProps<{}>();
</script>

<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-info w-100">Run Local Backup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>

<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1>Hello World</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

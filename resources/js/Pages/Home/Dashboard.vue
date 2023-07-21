<template>
    <div>
        <Head title="Dashboard" />
        <div class="row">
            <div class="col-12">
                <h4 class="text-center text-md-left">Dashboard</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button
                            class="btn btn-dark"
                            @click="pushAlert('success', 'this is an alert')"
                        >
                            New Flash Alert
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
// import { ref, reactive, onMounted } from 'vue';
import { pushAlert } from "@/State/LayoutState";

// const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

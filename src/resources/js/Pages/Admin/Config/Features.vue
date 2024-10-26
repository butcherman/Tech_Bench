<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Enable/Disable Features</div>
                    <FeatureForm :feature-list="featureList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import FeatureForm from "@/Forms/Admin/Config/FeatureForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    featureList: {
        [key: string]: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

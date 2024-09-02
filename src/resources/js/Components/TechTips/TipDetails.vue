<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Details:
                        <div
                            v-if="outOfDate"
                            class="text-center alert alert-danger"
                        >
                            <RefreshButton
                                :only="[
                                    'tip-data',
                                    'tip-equipment',
                                    'tip-files',
                                ]"
                                @click="$emit('refreshed')"
                            />
                            Data Updated. Please Refresh Page
                        </div>
                    </div>
                    <div v-html="tipData.details" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";

defineEmits(["refreshed"]);
defineProps<{
    tipData: techTip;
    outOfDate?: boolean;
}>();
</script>

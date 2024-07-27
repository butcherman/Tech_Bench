<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Details:</div>
                    <div v-html="tipData.details" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tipData: techTip;
}>();
</script>

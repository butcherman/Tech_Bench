<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Equipment:</div>
            <ShowEquipment />
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, reactive, onMounted } from 'vue';
    import ShowEquipment from './ShowEquipment.vue';
</script>

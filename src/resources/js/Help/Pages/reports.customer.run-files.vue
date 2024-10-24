<template>
    <div class="text-center">
        <h5>Customer Files Report</h5>
        <p>
            This report shows all customer that have or are missing a specific
            file type.
        </p>
        <p>
            This report can be useful for determining which customers are
            missing backup files.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

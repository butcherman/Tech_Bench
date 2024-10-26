<template>
    <div class="text-center">
        <h5>Disabled Customers</h5>
        <p>
            All customers that have been disabled are listed here. They can be
            re-activated, or completely removed along with all attached data.
        </p>
        <p>
            <strong class="text-danger">Important Note:</strong> Deleting a
            customer is permanent and cannot be undone.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

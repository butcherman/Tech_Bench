<template>
    <div class="text-center">
        <h5>Deleted Tech Tips</h5>
        <p>
            Tech Tips that have been deleted are kept here for review. These
            tips are no longer available to users, but can be re-enabled or
            permanently deleted.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<template>
    <div id="manage-customer-dropdown" class="dropdown">
        <button
            class="btn rounded-circle dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <ul class="dropdown-menu">
            <li>Edit Customer</li>
            <li>Edit Site</li>
            <li>Manage Deleted Items</li>
            <li>Create Customer Alert</li>
            <li>Disable Site</li>
            <li>Disable Customer</li>
        </ul>
    </div>
</template>

<script setup lang="ts">
// import { ref, reactive, onMounted } from "vue";
// const props = defineProps<{}>();
</script>

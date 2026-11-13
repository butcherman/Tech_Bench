<script setup lang="ts">
const title = "Customer File Types";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            When a user uploads a customer file, they will be asked for the file
            type.
        </p>
        <p class="text-center">Default File Types are:</p>
        <div class="d-flex justify-content-center">
            <ul class="list-disc">
                <li>Equipment Backup</li>
                <li>License</li>
                <li>Site Map</li>
                <li>Other</li>
            </ul>
        </div>
        <p class="text-center">You can add and remove File Types as needed.</p>
        <p class="text-center">
            <strong>Note:</strong> You cannot remove a File Type if it is in
            use.
        </p>
    </div>
</template>

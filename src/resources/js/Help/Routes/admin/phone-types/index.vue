<script setup lang="ts">
const title = "Contact Phone Types";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            When a Contact is created with a phone number, the user must
            identify the phone type.
        </p>
        <p class="text-center">Default Phone Types are:</p>
        <div class="d-flex justify-content-center">
            <ul class="list-disc">
                <li>Home</li>
                <li>Work</li>
                <li>Mobile</li>
            </ul>
        </div>
        <p class="text-center">You can add and remove Phone Types as needed.</p>
        <p class="text-center">
            <strong>Note:</strong> You cannot remove a Phone Type if it is in
            use.
        </p>
    </div>
</template>

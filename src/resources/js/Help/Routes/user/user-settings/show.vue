<script setup lang="ts">
const title = "Help";

defineProps<{
    twoFa: {
        enabled: boolean;
        allowSaveDevice: boolean;
    };
}>();

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p class="text-center">
                User Settings allows you to adjust your personal settings to
                your liking.
            </p>
            <div v-if="twoFa.enabled && twoFa.allowSaveDevice">
                <p>
                    When Using Two-Factor Authentication, all registered devices
                    (using the Remember Device flag) are listed here.
                </p>
                <p>
                    Click the
                    <fa-icon icon="xmark" class="text-danger" /> to remove a
                    device forcing Two-Factor Authentication on the next login.
                </p>
            </div>
        </div>
    </div>
</template>

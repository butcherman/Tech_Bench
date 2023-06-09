<template>
    <div>
        <slot />
    </div>
</template>

<script setup lang="ts">
// import '../../scss/Layouts/guestLayout.scss';
    // import { ref, reactive, onMounted } from 'vue';

    // const props = defineProps<{}>();
</script>

<style lang="scss">
body {
    height: 100vh;
    width: 100vw;

    & {
        background: #090321;
        background: -moz-linear-gradient(-45deg, #090321 0%, #424242 50%, #5E5453 100%);
        background: -webkit-linear-gradient(-45deg, #090321 0%, #424242 50%, #5E5453 100%);
        background: linear-gradient(135deg, #090321 0%, #424242 50%, #5E5453 100%);
        background-attachment: fixed;
        min-height: 100vh;
    }
}
</style>

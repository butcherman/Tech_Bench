<template>
    <div class="guest-layout">
        <slot />
    </div>
</template>

<script>
    export default {
        metaInfo: {
            title: 'Welcome',
            titleTemplate: '%s | Tech Bench',
        }
    }
</script>

<template>
    <b-button variant="info" @click="goBack"><i class="fas fa-arrow-left"></i> Go Back</b-button>
</template>

<script>
    export default {
        methods: {
            goBack()
            {
                parent.history.back();
            }
        }
    }
</script>

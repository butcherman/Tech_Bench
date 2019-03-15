<template>
    <b-button variant="info" @click="goBack">Go Back</b-button>
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

<template>
    <div>
        <p>A Tech Tip has been updated with new information.</p>
        <p>Subject:  <strong>{{data.tip_data.subject}}</strong></p>
        <div class="text-center mt-4">
            <inertia-link as="b-button" :href="route('tech-tips.show', data.tip_data.slug)" variant="info" @click="markMessage">Click to View the Tech Tip</inertia-link>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type:     Object,
                required: true,
            }
        },
        methods: {
            markMessage()
            {
                this.$emit('read');
            },
        },
    }
</script>

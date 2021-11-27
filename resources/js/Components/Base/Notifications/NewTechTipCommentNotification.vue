<template>
    <div>
        <p>{{data.user.full_name}} has commented on Tech Tip - {{data.tip.subject}}</p>
        <p>The comment is:</p>
        <p>{{data.comment.comment}}</p>
        <div class="text-center mt-4">
            <inertia-link as="b-button" :href="route('tech-tips.show', data.tip.slug)" variant="info" @click="markMessage">Click here to review the comment</inertia-link>
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

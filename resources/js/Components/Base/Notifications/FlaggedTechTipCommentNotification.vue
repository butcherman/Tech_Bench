<template>
    <div>
        <p>User {{data.user}} has flagged a comment on a Tech Tip as inappropriate.</p>
        <p>The comment is:</p>
        <p>{{data.comment}}</p>
        <div class="text-center mt-4">
            <inertia-link as="b-button" :href="route('tips.comments.index')" variant="info" @click="markMessage">Click here to review the comment and take the appropriate action</inertia-link>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Object,
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

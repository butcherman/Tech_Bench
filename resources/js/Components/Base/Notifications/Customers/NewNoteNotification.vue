<template>
    <div>
        <p>A new Customer Note has been created for {{data.customer}}</p>
        <div class="text-center mt-4">
            <inertia-link as="b-button" :href="route('customers.show', data.slug)" variant="info" @click="markMessage">Click to view Customer</inertia-link>
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

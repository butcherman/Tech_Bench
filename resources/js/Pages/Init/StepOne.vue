<template>
    <InitLayout :step-id="1">
        <div class="card-title">Welcome to the Tech Bench</div>
        <p class="text-center">
            The Tech Bench is a Web Application built to assist
            service technicians by allowing them to store and
            share information about their customers and equipment
            they install and maintain.
        </p>
        <p class="text-center">
            Before you can start using the Tech Bench, we need to
            do some basic setup first.
        </p>
        <p class="text-center">
            <Link
                :href="$route('init.step-2')"
                class="btn btn-primary mt-4"
            >
                Setup Tech Bench
            </Link>
        </p>
    </InitLayout>

</template>

<script setup lang="ts">
    import InitLayout from './InitLayout.vue';

    const $route = route;
</script>

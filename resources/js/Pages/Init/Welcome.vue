<template>
    <div>
        <div class="card-title">Welcome to the Tech Bench</div>
        <p class="text-center">
            The Tech Bench is a Web Application built to assist service
            technicians by allowing them to store and share information about
            their customers and equipment they install and maintain.
        </p>
        <p class="text-center">
            Before you can start using the Tech Bench, we need to do some basic
            setup first.
        </p>
        <p class="text-center">
            <Link :href="$route('init.step-1')" class="btn btn-primary mt-4">
                Setup Tech Bench
            </Link>
        </p>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

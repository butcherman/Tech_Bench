<template>
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div v-if="image" class="col-6 col-md-3 text-center">
                            <img
                                :src="`/images/error/${image}`"
                                class="img-fluid"
                            />
                        </div>
                        <div class="col">
                            <h1 class="text-center">{{ title }}</h1>
                            <h4 class="text-center">{{ description }}</h4>
                            <div class="text-center mt-4">
                                <button class="btn btn-primary" @click="goBack">
                                    <fa-icon icon="left-long" />
                                    Go Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ref, computed, onMounted } from "vue";

const props = defineProps<{
    status: number;
    message?: string;
}>();

const title = computed(() => {
    let status = {
        503: "503 | Service Unavailable",
        500: "500 | Server Error",
        404: "404 | Page Not Found",
        403: "403 | Forbidden",
    }[props.status];

    if (!status) {
        return props.status;
    }

    return status;
});

const description = computed(() => {
    let msg = {
        503: "Sorry, we are doing some maintenance behind the curtain. Please check back soon.",
        500: "Whoops, something bad happened.  Our minions are hard at work to determine what went wrong",
        404: "I cannot seem to find the page you are looking for",
        403: "Sorry, you do not appear to belong here",
    }[props.status];

    if (!msg) {
        return "Well, this is not good.  Something bad happened and our minions are hard at work to determine what went wrong.  Please try again later";
    }

    return msg;
});

const image = computed(() => {
    return {
        503: "sleep.png",
        500: "oops.png",
        404: "searching.png",
        403: "no.png",
    }[props.status];
});

const goBack = () => {
    window.history.back();
};
</script>

<script lang="ts">
export default { layout: GuestLayout };
</script>

<style scoped lang="scss">
img {
    max-height: 250px;
}
</style>

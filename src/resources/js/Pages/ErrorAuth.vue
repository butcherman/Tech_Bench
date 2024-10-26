<template>
    <div class="row justify-content-center">
        <Head :title="`Error - ${status}`" />
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div>
                        <img :src="image" class="float-start img-fluid w-25" />
                        <h3
                            class="h-100 text-center d-flex flex-column justify-content-center"
                        >
                            {{ title }}
                        </h3>
                    </div>
                    <hr />
                    <div>
                        <h6 class="text-center">
                            {{ description }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";

const props = defineProps<{
    status: number;
    message: string;
}>();

const title = computed(() => {
    return {
        503: "503 | Service Unavailable",
        500: "500 | Server Error",
        404: "404 | Page Not Found",
        403: "403 | Forbidden",
    }[props.status];
});

const image = computed(() => {
    return {
        503: "/images/error/sleep.png",
        500: "/images/error/oops.png",
        404: "/images/error/searching.png",
        403: "/images/error/no.png",
    }[props.status];
});

const description = computed(() => {
    if (props.message) {
        return props.message;
    }

    return {
        503: "Sorry, we are doing some maintenance behind the curtain. Please check back soon.",
        500: "Whoops, something bad happened.  Our minions are hard at work to determine what went wrong",
        404: "I cannot seem to find the page you are looking for",
        403: "Sorry, you do not appear to belong here",
    }[props.status];
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

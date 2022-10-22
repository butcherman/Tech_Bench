<template>
    <Head :title="`Error ${status}`" />
    <Guest>
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-center">{{title}}</h1>
                            <h4 class="text-center mt-4">{{description}}</h4>
                            <div class="text-center mt-4">
                                <Link as="button" class="btn btn-primary w-25" href="/">
                                    Return Home
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Guest>
</template>

<script setup lang="ts">
    import Guest from '@/Layouts/guest.vue';
    import { ref, computed } from 'vue';

    const props = defineProps<{
        status : number;
        message: string;
    }>();

    const title = computed(() => {
        return {
            500: '500: Server Error',
            503: '503: Service Unavailable',
            404: '404: Page Not Found',
            403: '403: Forbidden',
            429: '429: Too Many Requests',
        }[props.status];
    });

    const description = computed(() => {
        return {
            500: 'Whoops, something bad happened.  Our minions are hard at work to determine what went wrong',
            503: 'We are working behind the curtain to make things better.  Please try again later',
            404: 'Well this is embarrassing.  I cannot seem to find the page you are looking for',
            403: props.message ? props.message
                    : 'Looks like you are trying to access an area you do not have permissions for',
            429: 'Too many requests have been made.  You must wait at least 2 hours to try again',
        }[props.status];
    });
</script>

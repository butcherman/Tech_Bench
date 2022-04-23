<template>
    <div class="row justify-content-center align-items-center" id="auth-layout-container">
        <div class="col-md-10">
            <h1 class="text-center">{{title}}</h1>
            <h4 class="text-center mt-4">{{description}}</h4>
            <div class="text-center mt-4">
                <inertia-link as="b-button" href="/">Return Home</inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        /**
         * status message number - i.e. 200, 404, etc
         */
        status: {
            type:     Number,
            required: true,
        },
        /**
         * custom message sent with error
         */
        message: {
            type:     String,
            required: false,
            default:  null,
        }
    },
    computed: {
        /**
         * return name of error based on the server error number
         */
        title() {
            return {
                503: '503 | Service Unavailable',
                500: '500 | Server Error',
                404: '404 | Page Not Found',
                403: '403 | Forbidden',
            }[this.status]
        },
        /**
         * return generic message based on the server error
         */
        description() {
            return {
                503: 'Sorry, we are doing some maintenance behind the curtain. Please check back soon.',
                500: 'Whoops, something bad happened.  Our minions are hard at work to determine what went wrong',
                404: 'I cannot seem to find the page you are looking for',
                403: 'Sorry, you do not appear to belong here',
            }[this.status]
        }
    },
}
</script>

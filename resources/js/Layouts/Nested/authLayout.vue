<template>
    <div class="row justify-content-center align-items-center login-form-container">
        <div class="col-lg-8 col-xl-6">
            <div class="row" id="header-title">
                <div class="col-12"><h1>{{$page.props.app.name}}</h1></div>
            </div>
            <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
                <div class="col-md-6">
                    <img :src="$page.props.app.logo" alt="Company Logo" id="header-logo" />
                </div>
                <div class="col-md-6">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            //
        },
        data() {
            return {
                //
            }
        },
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            //
        },
        metaInfo: {
            title: 'Welcome',
        }
    }
</script>

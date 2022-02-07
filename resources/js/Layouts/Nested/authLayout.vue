<template>
    <div class="row justify-content-center align-items-center" id="auth-layout-container">
        <div class="col">
            <div class="row justify-content-center">
                <h1>{{$page.props.app.name}}</h1>
            </div>
            <div class="row justify-content-center" id="auth-layout-sub-container">
                <div class="col col-lg-3 col-10">
                    <img :src="$page.props.app.logo" alt="Company Logo" id="header-logo" />
                </div>
                <div class="col col-lg-3 col-10">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        metaInfo: {
            title: 'Welcome',
        }
    }
</script>

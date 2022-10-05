<template>
    <Guest>
        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col">
                    <h1 class="text-center text-light">{{$page.props.app.name}}</h1>
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col col-12 col-md-6 text-center">
                                    <img :src="$page.props.app.logo" alt="Company Logo" id="header-logo" class="img-fluid" />
                                </div>
                                <div class="col col-12 col-md-6">
                                    <slot />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Guest>
</template>

<script setup lang="ts">
    import Guest from './guest.vue';
</script>

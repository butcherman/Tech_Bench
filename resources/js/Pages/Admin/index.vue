<template>
    <div>
        <div class="row">
            <div class="col-md-12 grid-margin">
                <h4 class="text-center text-md-left">Administration</h4>
            </div>
        </div>
         <div class="row" v-for="(options, key) in links" :key="key">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{key}}:</div>
                        <ul>
                            <li v-for="opt in options" :key="opt.name">
                                <inertia-link :href="opt.link" class="text-secondary"><i :class="opt.icon"></i> {{opt.name}}</inertia-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            links: {
                type: Object,
                required: true,
            }
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
        }
    }
</script>

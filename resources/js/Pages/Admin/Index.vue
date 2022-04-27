<template>
    <div>
        <div class="row">
            <div class="col-md-12 grid-margin">
                <h4>Administration</h4>
            </div>
        </div>
        <div v-for="(options, key) in links" :key="key" class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            {{key}}:
                        </div>
                        <ul>
                            <li v-for="(opt, key2) in options" :key="key2">
                                <inertia-link :href="opt.link" class="text-secondary">
                                    <i :class="opt.icon"></i>
                                    {{opt.name}}
                                </inertia-link>
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
            /**
             * Action generates all admin links based on users permission levels
             */
            links: {
                type:     Object,
                required: true,
            }
        },
        metaInfo: {
            title: 'Administration',
        },
    }
</script>

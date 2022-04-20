<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Reports</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">User Reports</div>
                        <b-list-group>
                            <b-list-group-item>
                                <inertia-link as="b-button" :href="route('reports.user-login-report.index')" block variant="info">User Login Summary Reports</inertia-link>
                            </b-list-group-item>
                            <!-- <b-list-group-item>
                                <inertia-link as="b-button" block variant="info">User Contributions Report</inertia-link>
                            </b-list-group-item> -->
                        </b-list-group>
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
        }
    }
</script>

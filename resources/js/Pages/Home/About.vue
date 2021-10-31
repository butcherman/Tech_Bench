<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">About Tech Bench</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <img src="/images/TechBenchLogo.png" class="mx-auto d-block img-fluid" alt="Tech Bench"/>
                        <p class="text-center">
                            Tech Bench &copy; 2016-2021
                            <span class="d-inline-block">Butcherman - All Rights Reserved</span>
                        </p>
                        <p class="text-center">{{version}} (Build {{build}})</p>
                        <p class="text-center">Build Date - {{build_date}}</p>
                        <p class="text-center">
                            Looking for guidance?
                            <span class="d-inline-block">
                                <a href="https://tech-bench.readthedocs.io/en/latest/user/index.html" target="_blank">Click here for the Tech Bench User Guide.</a>
                            </span>
                        </p>
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
            version: {
                type: String,
                required: true,
            },
            build: {
                type: String,
                required: true,
            },
            build_date: {
                type: String,
                required: true,
            }
        },
        metaInfo: {
            title: 'About',
        }
    }
</script>

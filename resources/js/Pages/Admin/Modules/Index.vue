<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Tech Bench Modules</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Loaded Modules
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 grid-margin">
                <b-button block variant="info" @click="checkForModules">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="checking"></span>
                        {{checkingText}}
                    </b-button>
            </div>
        </div>
        <b-modal
            id="get-modules-modal"
            ref="get-modules-modal"
            title="Available Tech Bench Modules"
            hide-footer
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <h3 class="text-center">{{moduleText}}</h3>
                    <atom-loader></atom-loader>
                    <h3 class="text-center">Please Wait...</h3>
                </template>
                <b-list-group>
                    <b-list-group-item v-for="(mod, key) in moduleList" :key="key">
                        <b-button title="Click for More Information" v-b-tooltip.hover variant="info" block v-b-toggle="'module'+key">
                            {{mod.module_name}}
                            <b-badge class="float-right" pill variant="light">v{{mod.module_version}}</b-badge>
                        </b-button>
                        <b-collapse :id="'module'+key">
                            <div class="card">
                                <div class="card-body">
                                    {{mod.description}}
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <b-button @click="installModule(mod)" variant="info">Install Module</b-button>
                            </div>
                        </b-collapse>
                    </b-list-group-item>
                </b-list-group>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            //
        },
        data() {
            return {
                loading: false,
                checking: false,
                moduleList: {},
                moduleText: null,
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
            checkingText()
            {
                if(!this.checking)
                {
                    return 'Check For Modules Online';
                }

                return 'Checking...';
            }
        },
        watch: {
             //
        },
        methods: {
            checkForModules()
            {
                this.checking = true;
                axios.get(route('admin.modules.get-online'))
                    .then(res => {
                        console.log(res);
                        this.moduleList = res.data;
                        this.$refs['get-modules-modal'].show();
                        this.checking = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            installModule(module)
            {
                console.log('Installing Module');
                console.log(module);

                this.moduleText = 'Downloading Module';
                this.loading = true;
                axios.post(this.route('admin.modules.download'), module)
                    .then(res => {
                        console.log(res);
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        }
    }
</script>

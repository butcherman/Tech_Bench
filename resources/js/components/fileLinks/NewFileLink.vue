<template>
    <b-form @submit="submitFile" method="post" :action=submit_route enctype="multipart/form-data">
        <input type="hidden" name="_token" :value=token />
        <b-form-group id="name"
                      label="Link Name:"
                      label-for="link_name">
            <b-form-input id="link_name"
                          type="text"
                          name="name"
                          placeholder="Enter A User Friendly Name For This Link"
                          required
                          v-model= name>
            </b-form-input>
        </b-form-group>
        <b-form-group id="expire"
                      label="Expires On:"
                      label-for="link_expire">
            <b-form-input id="link_expire"
                          type="date"
                          name="expire"
                          required
                          v-model=expire>
            </b-form-input>
        </b-form-group>
        <b-form-group id="tag-customer"
                      label="Link to Customer:"
                      label-for="customer-tag">
            <div class="input-group">
                <b-form-input id="customer-tag"
                              type="search"
                              name="customer_tag"
                              placeholder="Enter A Customer Number or Name (Optional)"
                              autocomplete="off"
                              v-model="customerTag"
                              @blur="searchCustomer"></b-form-input>
                <span class="input-group-append" id="search-for-customer">
                    <button class="btn btn-outline-secondary border-left-0 border" id="search-for-customer-button" type="button" tabindex="-1" @click="searchCustomer">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </b-form-group>
        <div class="row justify-content-center">
            <div class="col-5">
                <label class="switch">
                    <input type="checkbox" name="allowUp" checked>
                    <span class="slider round"></span>
                </label>
                Allow User to Upload Files
            </div>
        </div>
        <vue-dropzone id="dropzone"
                      class="filedrag"
                      ref="myVueDropzone"
                      v-on:vdropzone-total-upload-progress="updateProgressBar"
                      v-on:vdropzone-sending="sendingFiles"
                      v-on:vdropzone-queue-complete="queueComplete"
                      :options="dropzoneOptions">
        </vue-dropzone>
        <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
        <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.text}}</b-button>
        <b-modal id="customer-selector-modal" title="Select A Customer" ref="customerSelectorModal">
            <ul class="list-group" v-for="cust in availableCustomers">
                <li class="list-group-item"><a href="#" :data-value="cust.cust_id+' - '+cust.name" @click="pickCustomer">{{cust.cust_id}} - {{cust.name}}</a></li>
            </ul>
        </b-modal>
    </b-form>
</template>

<script>
    export default {
        props: [
            'submit_route',
            'search_route',
            'detail_route',
            'expire_date'
        ],
        data: function () {
            return {
                dropzoneOptions: {
                    url: this.submit_route,
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
                customerTag: '',
                token: window.techBench.csrfToken,
                availableCustomers: [],
                cust: '',
                name: '',
                expire: this.expire_date,
                progress: 0,
                showProgress: false,
                button: {
                    text: 'Create File Link',
                    dis: false
                }
            }
        },
        methods: {
            submitFile(e) 
            {
                e.preventDefault();
                var myDrop = this.$refs.myVueDropzone; //.processQueue();
                var myForm = new FormData(document.querySelector("form"));
                this.button.text = 'Loading...';
                this.button.dis = true;

                if (myDrop.getQueuedFiles().length > 0) 
                {
                    this.showProgress = true;
                    myDrop.processQueue();
                }
                else 
                {
                    myForm.append('_completed', true);
                    axios.post(this.submit_route, myForm)
                        .then(res => {
                            var url = this.detail_route.replace(':id', res.data.link).replace(':name', res.data.name);
                            window.location.href = url;
                        })
                        .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error))
                }
            },
            sendingFiles(file, xhr, formData) 
            {
                formData.append('_token', this.token);
                formData.append('name', this.name);
                formData.append('expire', this.expire);
                formData.append('customer_tag', this.customerTag);
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            queueComplete(file)
            {
                var myForm = new FormData(document.querySelector("form"));
                myForm.append('_completed', true);
                
                this.button.text = 'Processing, please wait....';
                axios.post(this.submit_route, myForm)
                        .then(res => {
                            var url = this.detail_route.replace(':id', res.data.link).replace(':name', res.data.name);
                            window.location.href = url;
                        })
                        .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error))
            },
            searchCustomer() {
                if (this.customerTag == '') 
                {
                    this.customerTag = 'NULL';
                }
                var url = this.search_route.replace(':id', this.customerTag);
                axios.get(url)
                    .then(res => {
                        this.$refs.customerSelectorModal.show();
                        this.availableCustomers = res.data;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            pickCustomer(e) {
                e.preventDefault();
                this.customerTag = e.currentTarget.dataset.value;
                this.$refs.customerSelectorModal.hide();
            }
        },
    }
</script>

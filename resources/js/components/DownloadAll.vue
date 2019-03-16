<template>
    <b-button variant="info" @click="downloadAll">Download All</b-button>
</template>

<script>
    export default {
        props: 
        {
            download_all_route: {required: true},
            file_arr: {type: Array, required: true},
        },
        methods: {
            downloadAll()
            {
                if(this.file_arr.length != 0)
                {
                    axios.put(this.download_all_route, {
                        fileArr: this.file_arr
                    })
                    .then(res => {
                        console.log(res);
                        window.location.href = this.download_all_route
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
                }
            }
        }
    }
</script>

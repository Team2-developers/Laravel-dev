<template>
    <div class="content">
        <h1>File Upload</h1>
        <p><input type="file" v-on:change="fileSelected"></p>
        <button v-on:click="fileUpload">アップロード</button>
        <p v-show="showUserImage"><img v-bind:src="user.file_path"></p>
    </div>
</template>

<script>
export default {
    data: function(){
        return {
          fileInfo: '',
          user: '',
          showUserImage: false
        }
    },
    methods:{
        fileSelected(event){
            this.fileInfo = event.target.files[0]
        },
        fileUpload(){
            const formData = new FormData()

            formData.append('file',this.fileInfo)

            axios.post('/api/fileupload',formData)
                .then(response =>{
                this.user = response.data
                if(response.data.file_path) this.showUserImage = true
            })
            .catch(error => {
            console.error("There was a problem uploading the file: ", error);
            });
        }
    }
}
</script>

<style>
.content{
    margin:5em;
}
</style>
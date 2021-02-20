<template>
    <form class="block" v-on:submit.prevent>
        <div class="main">
            <h4>Input URL</h4>
            <input name="url" id="url" v-model="url">
        </div>
        <div class="redirect">
            <div v-if="this.shorten_url === '0'">
                <span class="warning">Page not found - 404 error</span>
            </div>
            <div v-else-if="this.shorten_url === '-1'">
                <span class="error">Incorrect URL</span>
            </div>
            <div v-else>
                <a href="" v-model="shorten_url" @click="redirect()">{{this.shorten_url}}</a>
            </div>
            
        </div>
        <button class="createShorten-btn btn btn-primary" @click="createShortenURL()">Create Shorten URL</button>
        
    </form>
</template>

<script>

    export default{
        data(){
            return{
                url:"",
                shorten_url: "",
                redirect_url:""
            }
        },
        methods:{
            createShortenURL(){
                axios.post('/saveURL', {url: this.url}, ( xhr ) => {                    
                    // processing 
                    console.log("processing");
                },
                ( error ) => {
                    // called when loading has errors
                    console.error( 'An error happened', error );                    
                },)
                .then(res => {
                    //alert(res.data.result);
                    this.shorten_url = res.data.result;
                });
            },
            redirect(){
            axios.post('/getURL', {url: this.shorten_url}, ( xhr ) => {                    
                    // processing 
                    console.log("processing");
                },
                ( error ) => {
                    // called when loading has errors
                    console.error( 'An error happened', error );                    
                },)
                .then(res => {
                    this.redirect_url = res.data.result;
                    window.location.href = res.data.result;
                });
            }
        }
    }

</script>
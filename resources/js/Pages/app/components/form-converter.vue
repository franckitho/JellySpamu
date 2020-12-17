<template>
    <div>

        <form @submit="formSubmit" enctype="multipart/form-data" class="flex flex-col w-full">
            <input type="hidden" name="_token" :value="csrf">
            <div class="flex flex-row w-full">
                <label
                    class=" flex flex-row items-center px-4 py-0 bg-blue-500 rounded-full  tracking-wide cursor-pointer hover:bg-blue-600 text-white">
                    <p class="pr-2">Upload</p>
                    <i class="fas fa-upload"></i>
                    <input type="file" v-on:change="onImageChange" class="hidden"/>
                </label>
                <h3 class="text-white px-4 py-2 font-bold">OR</h3>
                <input
                    class="appearance-none w-full bg-white text-gray-900  py-3 px-4 leading-tight focus:outline-none rounded-full focus:bg-white"
                    type="text" v-model="form.url"  placeholder="Paste a video URL..." />
            </div>
            <div class="flex flex-row w-full">
                <div class="flex flex-col">
                </div>
                <div class="flex flex-col">
                    <div class="inline-block relative w-64">
                        <select
                            class="block appearance-none w-full bg-white  hover:border-gray-500 px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline"
                            v-model="form.export"
                            >
                            <option value="1080x1920">Export for TikTok</option>
                            <option value="1920x1080">Export for Youtube</option>
                            <option value="600x600">Export fo Instagram</option>
                            <option value="1080x1920">Export for Snapchat</option>
                            <option value="1280x720">Export for Facebook</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    <input class="flex flex-row items-center  px-4 py-0 font-bold bg-blue-500 rounded-full  tracking-wide cursor-pointer hover:bg-blue-600 text-white" type="submit">
                     Convertir <i class="fas fa-sync ml-3" />
                   
                </div>
            </div>







        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                form: {
                    url: null,
                    image: null,
                    export: null,
                },
            }
        },
        methods: {
            onImageChange(e){
                console.log(e.target.files[0]);
                this.form.image = e.target.files[0];
            },
            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;
 
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
 
                let formData = new FormData();
                formData.append('video', this.form.image);
                formData.append('url', this.form.url);
                formData.append('export', this.form.export);
 
                this.$inertia.post('/video', formData, config)
            }
        }
    };

</script>

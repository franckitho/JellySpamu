<template>
    <div class="flex flex-col w-full justify-items-center">
        <div class="flex flex-row w-full">
            <label
                class=" flex flex-row items-center px-4 py-0 bg-blue-500 rounded-full  tracking-wide cursor-pointer hover:bg-blue-600 text-white">
                <p class="pr-2">Upload </p>
                <i class="fas fa-upload"></i>
                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="hidden" />
            </label>
            <h3 class="text-white px-4 py-2 font-bold">OR</h3>
            <input
                class="appearance-none w-full bg-white text-gray-900  py-3 px-4 leading-tight focus:outline-none rounded-tl-full rounded-bl-full focus:bg-white"
                type="text" v-model="form.url" placeholder="Paste a video URL..." />

            <button
class="flex flex-row items-center px-4 py-0  bg-blue-500 rounded-tr-full rounded-br-full focus:outline-none   cursor-pointer hover:bg-blue-600 text-white"
                v-on:click="submitFile()"><span v-if="!inLoad">Validate</span><span v-if="inLoad" class="text-transparent">Valida</span><i v-if="inLoad" class=" animate-spin fas fa-circle-notch "></i></button>
        </div>
        <form  @submit="convertFile" enctype="multipart/form-data" class="flex flex-col w-full">
            <input type="hidden" name="_token" :value="csrf">
            <div v-if="step2 == true" class="flex flex-row  justify-between mb-4">
                <div class="flex flex-col  mt-4 mr-4 w-3/4">
                    <img class="object-fill rounded-lg " :src="'/storage/'+filedata.properties.preview" alt="">
                </div>
                <div class="flex flex-col mt-4 w-3/4 ">
                    <div class="container mx-auto w-full h-full bg-white rounded-lg">
                        <h3 class="uppercase pt-2 text-gray-600 font-semibold ml-3"> Metadata of {{ filedata.properties.name }} :</h3>
                        <div class="flex flex-row justify-between">
                            <div>
                                 <ul class="ml-3 text-sm text-gray-400">
                            <li>Size : <span class="font-semibold">{{filedata.properties.size}}</span> </li>
                            <li>Codec : <span class="font-semibold">{{filedata.properties.codec}}</span> </li>
                            <li>Duration : <span class="font-semibold">{{filedata.properties.duration}}</span> </li>
                            <li>Orientation : <span class="font-semibold">{{filedata.properties.orientation}}</span> </li>
                       
                        </ul>
                            </div>
                            <div>
                            <ul class="mr-3 text-sm text-gray-400">
                            <li>Resolution : <span class="font-semibold">{{filedata.properties.resolution}}</span></li>
                            <li>Framerate : <span class="font-semibold">{{filedata.properties. framerate}}</span></li>
                            <li>Bitrate : <span class="font-semibold">{{filedata.properties. bitrate}}</span></li>
                        </ul>

                            </div>
                        </div>
                        
                       
                    </div>
                    <div v-if="step2" class="flex flex-row mt-4 justify-start">
                        <div class="inline-block relative">
                            <select
                                class="block appearance-none w-full bg-white  hover:border-gray-500 px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline"
                                v-model="form.export">
                                <option value="null">Export for...</option>
                                <option value="1080x1920">Export for TikTok</option>
                                <option value="1920x1080">Export for Youtube</option>
                                <option value="600x600">Export fo Instagram</option>
                                <option value="1080x1920">Export for Snapchat</option>
                                <option value="1280x720">Export for Facebook</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <button
                            class="flex flex-row items-center ml-4  pl-4 pr-4 font-semibold bg-blue-500 rounded-full   cursor-pointer hover:bg-blue-600 text-white"
                            v-bind:class="'disabled_submit'" type="submit">
                            Convertir <i class="fas fa-sync ml-3" />
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    import axios from 'axios';
    import Button from '../../../Jetstream/Button.vue';
    export default {
        components: {
            Button
        },
        data() {
            return {
                filedata: {
                    properties:{
                        bitrate: "0",
                        codec: "Unknown",
                        duration: 0,
                        framerate: "00/00",
                        name: "File_name.mp4",
                        orientation: "Orientation",
                        preview: "preview/defaultvideo.png",
                        resolution: "0000x0000",
                        size: 0,
                    },
                    resource_id:'',
                },
                step2: false,
                inLoad : false,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                form: {
                    url: null,
                    image: null,
                    export: null,
                },
            }
        },
        methods: {
            convertFile(e) {
                e.preventDefault();
                let data = this.$inertia.get('/video/'+this.filedata.resource_id+'/convert')

            },
            handleFileUpload() {
                this.form.image = this.$refs.file.files[0];


            },
            submitFile() {
                let sendToBack = true;
                let formData = new FormData();
                formData.append('video', this.form.image);
                formData.append('url', this.form.url);
                formData.append('export', this.form.export);
                this.inLoad = true;
                if (sendToBack) {
        
                    axios.post('/video',
                            formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        ).then(response => {
                          
                            this.filedata = response.data
                            this.step2 = true
                            this.inLoad = false;
                        })
                        .catch(e => {
                            this.inLoad = false;
                      
                            this.errors.push(e)
                           
                            
                        })
                } else {
                    console.log("Saisie invalie")
                }


            }
        }
    };

</script>


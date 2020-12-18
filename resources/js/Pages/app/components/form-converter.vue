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
                v-on:click="submitFile()"><span v-if="!inLoad">Validate</span><span v-if="inLoad"
                    class="text-transparent">Valida</span><i v-if="inLoad"
                    class=" animate-spin fas fa-circle-notch "></i></button>
        </div>
        <form @submit="convertFile" enctype="multipart/form-data" class="flex flex-col w-full">
            <input type="hidden" name="_token" :value="csrf">
            <div v-if="step2== true" class="flex flex-row  justify-between mb-4">
                <div class="flex flex-col  mt-4 mr-4 w-3/4">
                    <img v-on:click="openMSystem()" class=" modal-open cursor-pointer object-fill rounded-lg "
                        :src="'/storage/'+filedata.properties.preview" alt="">
                </div>
                <div class="flex flex-col mt-4 w-3/4 ">
                    <div class="container mx-auto w-full h-48 bg-white rounded-lg">
                        <h3 class="uppercase pt-2 w-full text-center text-gray-600 font-semibold "> Metadata </h3>

                        <ul class="ml-3 text-md text-gray-400">
                            <li>Name : <span class="font-semibold">{{filedata.properties.name}}</span></li>

                        </ul>
                        <div class="flex flex-row justify-between">

                            <div>
                                <ul class="ml-3 text-md text-gray-400">
                                    <li class="pt-2">Resolution : <span
                                            class="font-semibold">{{filedata.properties.resolution}}</span></li>
                                    <li class="pt-2">Duration : <span
                                            class="font-semibold">{{filedata.properties.duration}}</span></li>
                                    <li class="pt-2">Orientation : <span
                                            class="font-semibold">{{filedata.properties.orientation}}</span> </li>

                                </ul>
                            </div>
                            <div>
                                <ul class="mr-3 text-md text-gray-400">
                                    <li class="pt-1">Codec : <span
                                            class="font-semibold uppercase">{{filedata.properties.codec}}</span> </li>
                                    <li class="pt-1">Framerate : <span
                                            class="font-semibold">{{filedata.properties. framerate}}</span></li>
                                    <li class="pt-1">Bitrate : <span
                                            class="font-semibold">{{filedata.properties. bitrate}}</span></li>
                                    <li class="pt-1">Size : <span
                                            class="font-semibold">{{filedata.properties.size}}</span> </li>

                                </ul>

                            </div>
                        </div>


                    </div>
                    <div class="flex flex-row mt-4 justify-start">
                        <i v-if="downloadable ==2"
                            class="ml-2 mr3 mt-2 animate-spin text-white fas fa-circle-notch "></i>
                        <a v-if="downloadable == 3" :href="'/video/'+filedata.resource_id+'/download'"
                            class="flex h-full flex-row items-center  pr-4 bg-blue-500 rounded-full   cursor-pointer hover:bg-blue-600 text-white block appearance-none bg-white  hover:border-gray-500 px-4 py-2 shadow leading-tight focus:outline-none focus:shadow-outline">
                            Download <i class=" ml-3 fa fa-download" />
                        </a>
                        <div v-if="downloadable == 0" class="inline-block relative">
                            <select
                                class="block appearance-none w-full pr-14 bg-white  hover:border-gray-500 px-4 py-2 pr-8 rounded-full justify-items-start shadow leading-tight focus:outline-none focus:shadow-outline"
                                v-model="form.export">

                                <option value="youtube">Export for Youtube</option>
                                <option value="tiktok">Export for TikTok</option>
                                <option value="instagram">Export fo Instagram</option>
                                <option value="snapchat">Export for Snapchat</option>
                                <option value="facebook">Export for Facebook</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <img v-if="form.export =='youtube'" class="w-6 h-5 mr-2"
                                    src="https://assets.stickpng.com/images/580b57fcd9996e24bc43c545.png" alt="">
                                <img v-if="form.export =='tiktok'" class="w-5 h-5 mr-2"
                                    src="https://cdn4.iconfinder.com/data/icons/social-media-flat-7/64/Social-media_Tiktok-512.png"
                                    alt="">
                                <img v-if="form.export =='instagram'" class="w-5 h-5 mr-2"
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/1200px-Instagram_logo_2016.svg.png"
                                    alt="">
                                <img v-if="form.export =='snapchat'" class="w-5 h-5 mr-2"
                                    src="https://upload.wikimedia.org/wikipedia/fr/archive/a/ad/20190808214526%21Logo-Snapchat.png"
                                    alt="">
                                <img v-if="form.export =='facebook'" class="w-5 h-5 mr-2"
                                    src="https://assets.stickpng.com/thumbs/584ac2d03ac3a570f94a666d.png" alt="">

                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <button v-if="downloadable == 0"
                            class="flex flex-row items-center ml-4  pl-4 pr-4 font-semibold bg-blue-500 rounded-full   cursor-pointer hover:bg-blue-600 text-white"
                            v-bind:class="'disabled_submit'" type="submit">
                            Convertir <i class="fas fa-sync ml-3" />
                        </button>


                    </div>

                </div>
            </div>

        </form>

        <div
            class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-75"></div>
            <div class="modal-container bg-transparent w-auto mx-auto rounded  z-50 overflow-y-auto">



                <div class="flex justify-center items-center pb-3">
                    <h3 class="text-white  text-xl text-left font-semibold ">(Esc) for close</h3>
        
                </div>

                <canvas :width="getWidth" :height="getHeight"
                    class=" cursor-move rounded-lg mx-auto justify-self-center" id="canvas_preview"
                    :src="'/storage/'+filedata.properties.preview"></canvas>

            </div>
        </div>


        <input class="hidden" id="cursor_pos" value="" v-model="position">
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
                    properties: {
                        bitrate: "10000",
                        codec: "Unknown",
                        duration: 10000,
                        framerate: "00/00",
                        name: "File_name.mp4",
                        orientation: "Orientation",
                        preview: "defaultvideo.png",
                        resolution: "604x327",
                        size: 0,
                    },
                    resource_id: '',
                    status: '',
                },
                step2: false,
                inLoad: false,
                inLoadDownlad: false,
                downloadable: 0,
                position:"",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                form: {
                    url: null,
                    image: null,
                    export: "youtube",
                    x_pos: "150",
                    y_pos: "150",
                    
                },
            }
        },
        watch:{
            position(newPos,oldPos){
                console.log("New : "+newPos)
                console.log("Old : "+oldPos)
            }
        },
        computed: {
            getHeight() {
                let result = this.filedata.properties.resolution.split('x')[1]
                if(parseInt(result)>700){
                    result = String(parseInt(result)*0.6)
                }
                return result;
            },
            getWidth() {
                let result = this.filedata.properties.resolution.split('x')[0]
                if(parseInt(result)>700){
                    result = String(parseInt(result)*0.60)
                }
                return result;
            }
        },
        methods: {
            convertFile(e) {
                if (this.downloadable != 3) {
                    this.downloadable = 2;
                    e.preventDefault();
                         console.log("form pos"+this.position)
                    axios.get('/video/' + this.filedata.resource_id + '/convert')
                        .then(response => {
                            this.downloadable = 3;
                            console.log(response.data)
                        })
                        .catch(e => {
                            this.errors.push(e)
                        })
                }
            },
            handleFileUpload() {
                this.form.image = this.$refs.file.files[0];
            },
            submitFile() {

                this.inLoad = false;
                this.downloadable = 0;
                this.step2 = false;
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
               
            },

            openMSystem() {
                startInterest()
                var openmodal = document.querySelectorAll('.modal-open')
                for (var i = 0; i < openmodal.length; i++) {
                    openmodal[i].addEventListener('click', function (event) {
                        event.preventDefault()
                        console.log("Ouverture du modal")
                        toggleModal()
                        
                        
                    })
                }

                const overlay = document.querySelector('.modal-overlay')
                overlay.addEventListener('click', toggleModal)

                var closemodal = document.querySelectorAll('.modal-close')
                for (var i = 0; i < closemodal.length; i++) {
                    closemodal[i].addEventListener('click', toggleModal)
                }

                document.onkeydown = function (evt) {
                    evt = evt || window.event
                    var isEscape = false
                    if ("key" in evt) {
                        isEscape = (evt.key === "Escape" || evt.key === "Esc")
                    } else {
                        isEscape = (evt.keyCode === 27)
                    }
                    if (isEscape && document.body.classList.contains('modal-active')) {
                        toggleModal()
                    }
                };


                function toggleModal() {
                    const body = document.querySelector('body')
                    const modal = document.querySelector('.modal')
                    modal.classList.toggle('opacity-0')
                    modal.classList.toggle('pointer-events-none')
                    body.classList.toggle('modal-active')
             
                }

                function startInterest() {
                var sprite_src = "/img/marker.png";
                    var canvas = document.getElementById('canvas_preview');
                    var context = canvas.getContext("2d");
                    var gheight = canvas.getAttribute('height');
                    var gwidth = canvas.getAttribute('width');

                   
                    var mapSprite = new Image();
                    mapSprite.src = canvas.getAttribute('src');


                    var Marker = function () {
                        this.Sprite = new Image();
                        this.Sprite.src = sprite_src;
                        this.Width = 30;
                        this.Height = 30;
                        this.XPos = 0;
                        this.YPos = 0;
                    }

                    var Markers = new Array();
                    var mouseClicked = function (mouse) {
                        var rect = canvas.getBoundingClientRect();
                        var mouseXPos = (mouse.x - rect.left);
                        var mouseYPos = (mouse.y - rect.top);
                        var marker = new Marker();
                        marker.XPos = mouseXPos - (marker.Width / 2);
                        marker.YPos = mouseYPos - (marker.Height / 2);
        
                        var chain = String(parseInt(marker.XPos)+','+parseInt(marker.YPos))
                        document.getElementById("cursor_pos").value = chain
                        Markers.pop();
                        Markers.push(marker);
                    }

                    canvas.addEventListener("mousedown", mouseClicked, false);
                    context.font = "15px Arial";
                    context.textAlign = "center";

                    var main = function () {
                        draw();
                    };

                    var draw = function () {
                        context.fillStyle = "#000";
                        context.fillRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(mapSprite, 0, 0, gwidth, gheight);
                        Markers.forEach(tempMarker => {
                            context.drawImage(tempMarker.Sprite, tempMarker.XPos, tempMarker.YPos,
                                tempMarker
                                .Width,
                                tempMarker.Height);
                        });



                    }

                    setInterval(main, (1000 / 60));

                }
                
            },
        }
    };

</script>

<template>
    <div>

        <form @submit="formSubmit" enctype="multipart/form-data" class="flex flex-col w-full">
            <input type="hidden" name="_token" :value="csrf">
            <div class="flex flex-row w-full">
                <label
                    class=" flex flex-row items-center px-4 py-0 bg-blue-500 rounded-full  tracking-wide cursor-pointer hover:bg-blue-600 text-white">
                    <p class="pr-2">Upload </p>
                    <i class="fas fa-upload"></i>
                    <input type="file" v-on:change="onImageChange, step2 = true" class="hidden" />
                </label>
                <h3 class="text-white px-4 py-2 font-bold">OR</h3>
                <input
                    class="appearance-none w-full bg-white text-gray-900  py-3 px-4 leading-tight focus:outline-none rounded-full focus:bg-white"
                    type="text" v-on:change="step2=true" v-model="form.url" placeholder="Paste a video URL..." />
            </div>
            <div v-if="step2 == true" class="flex flex-row  justify-between mb-4">
                <div class="flex flex-col  mt-4 mr-4 ">
                    <img class="object-fill rounded-lg " src="/img/defaultvideo.png" alt="">
                </div>
                <div class="flex flex-col mt-4 w-3/4 ">
                    <div class="container mx-auto w-full h-full bg-white rounded-lg">
                        <h3 class="uppercase pt-2 text-gray-600 font-semibold ml-3"> Metadata :</h3>
                        <ul class="ml-3 text-sm text-gray-400">
                        <li >Name : <span class="font-semibold">{{file_name}}</span> </li>
                        <li>Size : <span class="font-semibold">{{file_size}}</span> </li>
                        <li>Type : <span class="font-semibold">{{file_type}}</span> </li>
                        <li>Duration : <span class="font-semibold">{{file_duration}}</span> </li>
                        <li>Orientation : <span class="font-semibold">{{file_orientation}}</span> </li>
                        <li>Resolution : <span class="font-semibold">{{file_witdh}} x {{file_height}}</span></li>
                        </ul>

                    </div>
                    <div class="flex flex-row mt-4 justify-start">
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
    export default {
        data() {
            return {
                step2 : false,
                file_name : "Default name",
                file_size: "20mo",
                file_type: "mp4",
                file_duration: "2m10",
                file_witdh: "1920",
                file_height: "1080",
                file_orientation: "Horizontal",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                form: {
                    url: null,
                    image: null,
                    export: null,
                },
            }
        },
        methods: {
            onImageChange(e) {
                console.log(e.target.files[0]);
                this.form.image = e.target.files[0];
                this.nextStep(e)
            },
            formSubmit(e) {
              
                e.preventDefault();
                let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
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


<style lang="csss">
    cur
</style>
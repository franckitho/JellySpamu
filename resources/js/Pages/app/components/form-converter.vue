<template>
    <div>
        <form>
            <div class="flex flex-col items-center w-full">
                <div class="flex w-full">
                    <button class="shadow bg-red-600 h-full hover:bg-red-700 focus:shadow-outline focus:outline-none text-white font-bold py-4 rounded-r-none rounded-bl-none px-6 text-xl rounded-md" type="button">
                        <i class="fas fa-upload"></i>
                    </button>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 text-3xl py-3 px-4 leading-tight focus:outline-none rounded-tr-md focus:bg-white" type="text" name="" placeholder="Lien de la vidéo a convertir...">
                </div>
                <div class="w-full h-px bg-black" />
                <div class="flex w-full">
                    <select class="appearance-none block w-full bg-gray-200 text-gray-700 rounded-bl-md text-3xl py-3 px-4 leading-tight focus:outline-none focus:bg-white" name="">
                        <option value="">TikTok</option>
                        <option value="">Youtube</option>
                        <option value="">Instagram</option>
                        <option value="">Snapchat</option>
                    </select>
                    <button class="flex items-center shadow bg-red-600 h-full hover:bg-red-700 focus:shadow-outline focus:outline-none text-white font-bold py-4 rounded-tr-none rounded-l-none px-6 text-xl rounded-md" type="button">
                        Convertir <i class="fas fa-sync ml-3" />
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    
}
</script>
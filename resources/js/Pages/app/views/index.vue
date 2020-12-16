<template>
    <div class="min-h-screen flex justify-center bg-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <div>
                <img class="mx-auto h-40 w-auto" src="/img/logo_blanc.png">
                <h2 class="mt-6 text-center text-5xl font-extrabold text-white-900">
                    Jellyspamu Video Converter
                </h2>
            </div>
             <form-converter />
        </div>
    </div>
</template>

<script>
    import FormConverter from '../components/form-converter.vue'
    export default {
        components: {
            FormConverter,
        },
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        }
    }

   
</script>
   
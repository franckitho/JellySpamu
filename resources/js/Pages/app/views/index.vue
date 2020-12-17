<template>
    <div>
        <nav-bar />
        
        <div class="min-h-screen flex justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full space-y-8">
                <div>
                    <img class="mx-auto h-40 w-auto" src="/img/logo.png">
                    <h2 class="mt-4 text-center text-3xl font-extrabold text-white">
                        Jellyspamu Video Converter
                    </h2>
                </div>
                <form-converter />
            </div>
        </div>
    </div>
    
</template>

<script>
    import FormConverter from '../components/form-converter.vue'
    import NavBar from '../components/navbar.vue'
    import Alert from '../components/alert.vue'
    export default {
        components: {
            FormConverter,
            NavBar,
            Alert,
        },
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        
    }
   
</script>
   

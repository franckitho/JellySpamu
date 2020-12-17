<template>
    <div>
        <nav-bar />
        <div class="min-h-screen flex justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full space-y-8">
              
                <form-converter />
            </div>
        </div>
    </div>
    
</template>

<script>
    import FormConverter from '../components/form-converter.vue'
    import NavBar from '../components/navbar.vue'
    export default {
        components: {
            FormConverter,
            NavBar,
        },
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        }
    }

   
</script>
   
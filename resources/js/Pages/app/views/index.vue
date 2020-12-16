<template>
<div class="min-h-screen flex justify-center bg-gray-800 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div>
      <img class="mx-auto h-40 w-auto" src="/img/logo_bis.png">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-white-900">
        Jellyspamu Video Converter
      </h2>
    </div>
    <form class="mt-8 space-y-6" action="#" method="POST">
      <input type="hidden" name="remember" value="true">
      <div class="rounded-md shadow-sm -space-y-px">
         <form action="/video/store" method="POST" enctype="multipart/form-data">
                <input type="hidden" :value="csrf">
                <input type="file" name="video">
                <input type="submit" value="Ok">
    </form>
     
      </div>
      <div>
        <button type="submit" class="group relative  flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      
          Sign in
        </button>
      </div>
    </form>
  </div>
  
</div>
</template>

<script>
    export default {
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        }
    }

   
</script>
   
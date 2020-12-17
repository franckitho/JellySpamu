<template>
    <transition name="slide-fade">
        <div  v-if="visible" class="fixed flex max-w-xs w-full mt-4 mr-4 top-0 right-0 bg-white rounded shadow p-4">
            <div class="mr-2 text-green-500"><i class="fas fa-check"></i></div>
            <div class="flex-1">zebi</div>
            <button class="ml-2 text-cool-gray-400 hover:text-cool-gray-500 focus:text-indigo-600 focus:outline-none" @click="visible=flase"><i class="far fa-times-circle"></i></button>
        </div>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            visible: true,
        }
    },
    props: {
        alert: Object
    },
    watch: {
            alert: {
                deep: true,
                handler() {
                    this.visile = true
                    setTimeout(() => this.visible = false, 1000)
                }
            }
        }
}
</script>

<style>
    .slide-fade-enter-active {
    transition: all .3s ease;
    }
    .slide-fade-leave-active {
    transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
    }
</style>
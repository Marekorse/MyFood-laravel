<template>
    <div
        @click.prevent="$emit('closeEvent')"
        class="flex items-center justify-center fixed left-0 bottom-0 w-screen h-screen overflow-hidden z-40">
        <div
            class="fixed left-0 bottom-0 w-full h-full bg-black opacity-80 z-40"></div>
        <transition name="fade">
            <div
                v-if="fade"
                class="bg-white rounded-lg sm:w-full md:w-3/4 lg:w-1/2 z-50 shadow">
                <div class="md:flex justify-around items-center p-4">
                    <div class="font-semibold text-gray-700 text-base">
                        {{ message }}
                    </div>
                    <hr />
                    <div class="mb-2 sm:mb-0 sm:ml-2">
                        <button
                            @click="$emit('submitEvent')"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 m-1 rounded">
                            {{ button }}
                        </button>
                        <button
                            @click.prevent="$emit('closeEvent')"
                            class="m-1 bg-transparent hover:bg-gray-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                            {{ $t('global.back') }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: { message: String, button: String },
    data: function () {
        return {
            fade: false,
        }
    },
    created() {
        document.body.classList.add('overflow-hidden')
        setTimeout(() => {
            this.fade = true
        }, 0.6)
    },
    destroyed() {
        document.body.classList.remove('overflow-hidden')
    },
}
</script>

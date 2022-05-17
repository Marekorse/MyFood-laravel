<template>
    <div
        class="flex items-center justify-center fixed left-0 bottom-0 w-screen h-screen z-40">
        <div
            class="fixed left-0 bottom-0 w-full h-full bg-black opacity-70 z-40"></div>
        <transition name="fade">
            <div v-if="fade" class="bg-white rounded-lg w-1/4 h-1/4 z-50">
                <div
                    class="flex flex-col w-full h-full justify-center items-center p-4">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-24 w-24 text-green-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p
                        class="text-gray-500 font flex justify-center text-center font-semibold">
                        {{ message }}
                    </p>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: { message: String },
    data: function () {
        return {
            fade: false,
        }
    },
    created() {
        setTimeout(() => {
            this.fade = true
        }, 0.6)
    },
}
</script>

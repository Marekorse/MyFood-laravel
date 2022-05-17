<template>
    <div>
        <transition name="fade">
            <div
                v-if="passwordRequire"
                class="fixed w-screen h-screen z-50 bg-black bg-opacity-95">
                <div
                    class="flex justify-center items-center content-center w-full h-full">
                    <div
                        class="bg-white rounded shadow p-8 m-4 max-w-xs text-center">
                        <div class="mb-4">
                            <h1>v príprave</h1>
                        </div>
                        <div class="mb-8">
                            <p>Stránka bude čoskoro pripravená.</p>
                        </div>
                        <input
                            @input="checkPassword"
                            v-model="password"
                            type="password"
                            class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:shadow-outline w-full"
                            placeholder="Password"
                            style="transition: all 0.15s ease 0s" />
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            passwordRequire: true,
            password: '',
            enterpassword: 'MyFood',
        }
    },
    methods: {
        checkPassword() {
            localStorage.password = this.password
            if (this.password === this.enterpassword) {
                this.passwordRequire = false
            }
        },
    },
    created() {
        if (localStorage.password) {
            if (this.enterpassword === localStorage.password) {
                this.passwordRequire = false
            }
        }
    },
}
</script>

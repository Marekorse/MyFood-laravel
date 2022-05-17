<template>
    <div>
        <div class="flex h-screen bg-white">
            <div class="w-1/2 h-full md:block hidden">
                <figure
                    class="w-full max-h-full flex items-center justify-center overflow-hidden">
                    <img
                        class="w-full"
                        :src="$app_url + 'storage/images/login/login.jpg'"
                        alt=""
                        srcset="" />
                </figure>
            </div>
            <div
                class="w-full md:w-1/2 max-w-lg mx-auto my-24 px-4 py-5 shadow-none">
                <div class="text-left p-0 font-sans">
                    <h1
                        class="text-gray-800 text-xl font-medium flex items-center h-28 overflow-hidden justify-center w-full">
                        <logo class="w-44"></logo>
                    </h1>
                    <h3 class="p-1 text-gray-700">
                        {{ $t('nav.login.welcomeBack') }}
                    </h3>
                </div>
                <form
                    @submit.prevent="login()"
                    method="POST"
                    action=""
                    class="p-0">
                    <div class="mt-5">
                        <div v-if="errors && errors.email">
                            <label
                                v-for="error in errors.email"
                                :key="error"
                                for="email"
                                class="flex items-center text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ error }}
                            </label>
                        </div>
                        <input
                            v-model="email"
                            name="email"
                            type="email"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="$t('nav.login.emailPlaceholder')" />
                    </div>
                    <div class="mt-5">
                        <div v-if="errors && errors.password">
                            <label
                                v-for="error in errors.password"
                                :key="error"
                                for="password"
                                class="flex items-center text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ error }}
                            </label>
                        </div>
                        <input
                            v-model="password"
                            type="password"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="
                                $t('nav.login.passwordPlaceholder')
                            " />
                    </div>
                    <div class="mt-10">
                        <button
                            type="submit"
                            class="py-3 gradient-green-blue flex items-center justify-center text-white w-full rounded">
                            <svg
                                v-if="loading"
                                fill="none"
                                class="w-10 h-10 animate-spin"
                                viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    clip-rule="evenodd"
                                    d="M15.165 8.53a.5.5 0 01-.404.58A7 7 0 1023 16a.5.5 0 011 0 8 8 0 11-9.416-7.874.5.5 0 01.58.404z"
                                    fill="currentColor"
                                    fill-rule="evenodd" />
                            </svg>
                            {{ $t('nav.login.loginButton') }}
                        </button>
                    </div>
                </form>
                <a
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                    :href="$app_url + 'forgot-password'">
                    {{ $t('nav.login.forgotPassword') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import logo from '../navigation/logo.vue'
export default {
    components: {
        logo,
    },
    data() {
        return {
            loading: false,
            password: '',
            email: '',
            errors: null,
        }
    },
    methods: {
        login() {
            this.loading = true
            axios
                .post(`${this.$app_url}prihlasenie`, {
                    password: this.password,
                    email: this.email,
                })
                .then((res) => {
                    document.location.href = '/'
                })
                .catch((err) => {
                    this.loading = false
                    this.errors = err.response.data.errors
                })
        },
    },
}
</script>

<template>
    <div>
        <div class="flex min-h-screen bg-white">
            <div class="w-1/2 md:block hidden overflow-hidden">
                <figure
                    class="w-full max-h-full flex items-center justify-center overflow-hidden">
                    <img
                        class="w-full"
                        :src="$app_url + 'storage/images/register/register.jpg'"
                        alt=""
                        srcset="" />
                </figure>
            </div>
            <div
                class="w-full md:w-1/2 max-w-lg mx-auto my-1 md:my-10 px-4 py-5 shadow-none">
                <div class="text-left font-sans">
                    <h1 class="text-gray-600 text-3xl font-semibold">
                        {{ $t('nav.register.title') }}
                    </h1>
                </div>
                <image-input @newImage="setImage"></image-input>
                <form
                    @submit.prevent="register()"
                    method="POST"
                    action=""
                    class="p-0">
                    <div class="mt-5">
                        <div v-if="errors && errors.name">
                            <label
                                v-for="error in errors.name"
                                :key="error"
                                for="name"
                                class="text-xs text-red-500 font-semibold"
                                >{{ error }}</label
                            >
                        </div>
                        <input
                            v-model="name"
                            type="text"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="$t('nav.register.namePlaceholder')"
                            autofocus />
                    </div>
                    <div class="mt-5">
                        <div v-if="errors && errors.email">
                            <label
                                v-for="error in errors.email"
                                :key="error"
                                for="email"
                                class="text-xs text-red-500 font-semibold"
                                >{{ error }}</label
                            >
                        </div>
                        <input
                            v-model="email"
                            type="email"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="
                                $t('nav.register.emailPlaceholder')
                            " />
                    </div>
                    <div class="mt-5">
                        <div v-if="errors && errors.password">
                            <label
                                v-for="error in errors.password"
                                :key="error"
                                for="password"
                                class="text-xs text-red-500 font-semibold"
                                >{{ error }}</label
                            >
                        </div>
                        <input
                            v-model="password"
                            type="password"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="
                                $t('nav.register.passwordPlaceholder')
                            " />
                    </div>
                    <div class="mt-5">
                        <div v-if="errors && errors.password_confirmation">
                            <label
                                v-for="error in errors.password_confirmation"
                                :key="error"
                                for="password_confirmation"
                                class="text-xs text-red-500 font-semibold"
                                >{{ error }}</label
                            >
                        </div>
                        <input
                            v-model="password_confirmation"
                            type="password"
                            class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                            :placeholder="
                                $t('nav.register.confirmPasswordPlaceholder')
                            " />
                    </div>
                    <div class="mt-6 block text-sm md:font-sans text-gray-800">
                        <span display="inline" class=""
                            >{{
                                $t('nav.register.onCreateAccountYouAcceptTerms')
                            }}
                            <a class="" href="">
                                <span class="underline">{{
                                    $t('nav.register.termsOfUse')
                                }}</span>
                            </a>
                            ,
                            <a
                                class=""
                                href=""
                                target="_blank"
                                data-test="Link">
                                <span class="underline">{{
                                    $t('nav.register.processingOfPersonalData')
                                }}</span></a
                            >
                        </span>
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
                            {{ $t('nav.register.registerButton') }}
                        </button>
                    </div>
                </form>
                <a class="" :href="$app_url + 'prihlasenie'" data-test="Link"
                    ><span
                        class="block p-5 text-center text-gray-800 text-xs"
                        >{{ $t('nav.register.alreadyRegistred') }}</span
                    ></a
                >
            </div>
        </div>
    </div>
</template>

<script>
import Logo from '../navigation/logo.vue'
import imageInput from './imageInput.vue'
export default {
    components: {
        imageInput,
        Logo,
    },

    data() {
        return {
            loading: false,
            name: '',
            password: '',
            email: '',
            password_confirmation: '',
            avatar: null,
            errors: null,
        }
    },
    methods: {
        register() {
            this.loading = true
            let formData = new FormData()
            if (this.avatar) {
                formData.append('avatar', this.avatar)
            }
            formData.append('name', this.name)
            formData.append('email', this.email)
            formData.append('password', this.password)
            formData.append('password_confirmation', this.password_confirmation)
            formData.append('_method', 'POST')
            axios
                .post(`${this.$app_url}registracia`, formData)
                .then((res) => {
                    document.location.href = '/'
                })
                .catch((err) => {
                    this.loading = false
                    this.errors = err.response.data.errors
                })
        },
        setImage(image) {
            this.avatar = image
        },
    },
}
</script>

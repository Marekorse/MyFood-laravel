<template>
    <div>
        <div class="flex items-center h-screen justify-center w-full">
            <div
                class="mx-2 w-full md:w-2/3 lg:h-1/3 p-4 shadow-lg bg-gray-200 rounded-lg">
                <figure
                    class="flex items-center justify-center overflow-hidden w-full max-h-32">
                    <img
                        class="w-44"
                        :src="$app_url + 'storage/images/logo/2.svg'"
                        alt="" />
                </figure>
                <div class="mb-4 text-sm text-gray-600">
                    {{ $t('auth.verifyEmail') }}
                </div>

                <transition name="fade">
                    <div
                        v-if="status === 'verification-link-sent'"
                        class="mb-4 font-medium text-sm text-green-600">
                        {{ $t('auth.verifyLinkWasSendFor') }} {{ email }}.
                    </div>
                </transition>

                <div class="mt-4 flex items-center justify-between">
                    <button
                        @click="sendVerificationEmail()"
                        class="text-blue-500 font font-semibold">
                        {{ $t('auth.againSendEmailLink') }}
                    </button>

                    <button
                        @click="logout()"
                        type="submit"
                        class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ $t('auth.logout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: { email: String },
    data: function () {
        return { status: null }
    },
    methods: {
        sendVerificationEmail() {
            axios
                .post(`${this.$app_url}email/verification-notification`)
                .then((res) => {
                    this.status = res.data.status
                })
        },
        logout() {
            axios.post('/logout').then((response) => {
                if (response.status === 200) {
                    window.location.href = '/'
                }
            })
        },
    },
}
</script>

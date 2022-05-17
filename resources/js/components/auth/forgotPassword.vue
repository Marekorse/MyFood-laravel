<template>
    <div>
        <div class="flex flex-col h-screen items-center justify-center w-full">
            <div class="mx-2 w-full sm:w-3/4 md:w-1/4 p-4">
                <figure
                    class="flex items-center justify-center overflow-hidden w-full max-h-32">
                    <img
                        class="w-44"
                        :src="$app_url + 'storage/images/logo/2.svg'"
                        alt="" />
                </figure>
                <p class="text-base text-gray-700">
                    {{ $t('auth.forgotPassword') }}
                </p>
            </div>
            <div class="mt-4">
                <div v-if="errors">
                    <label
                        v-for="error in errors"
                        :key="error"
                        for="email"
                        class="text-red-500 flex items-center text-xs">
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
                    id="email"
                    placeholder="@email.com"
                    class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                    type="email"
                    name="email"
                    required
                    autofocus />
            </div>
            <button
                @click.prevent="sendResetlink"
                class="mt-4 text-blue-500 font-semibold">
                {{ $t('auth.sendResetLink') }}
            </button>
        </div>
        <approved-modal
            :message="$t('auth.resetLinkWasSendFor') + email"
            v-if="status === 'success'" />
    </div>
</template>

<script>
import approvedModal from '../recipe/approvedModal'

export default {
    components: {
        approvedModal,
    },
    data: function () {
        return {
            errors: null,
            status: null,
            email: '',
        }
    },
    methods: {
        sendResetlink() {
            axios
                .post(`${this.$app_url}forgot-password`, { email: this.email })
                .then((res) => {
                    this.status = res.data.status
                    setTimeout(() => {
                        window.location.href = '/'
                    }, 4000)
                })
                .catch((err) => {
                    this.errors = err.response.data.errors.email
                })
        },
    },
}
</script>

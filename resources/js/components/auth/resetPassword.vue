<template>
    <div>
        <div class="flex items-center h-screen justify-center w-full">
            <div class="mx-2 w-full sm:w-3/4 md:w-1/4 p-4">
                <figure
                    class="flex items-center justify-center overflow-hidden w-full max-h-32">
                    <img
                        class="w-44"
                        :src="$app_url + 'storage/images/logo/2.svg'"
                        alt="" />
                </figure>
                <p class="text-xl font-semibold text-gray-700">
                    {{ $t('auth.newPassword') }} :
                </p>
                <div class="mt-4">
                    <div v-if="errors.email">
                        <label
                            v-for="error in errors.email"
                            :key="error"
                            for="email"
                            class="text-xs mx-1 text-red-500 font-semibold"
                            >{{ error }}</label
                        >
                    </div>
                    <input
                        :placeholder="$t('auth.emailPlaceholder')"
                        id="email"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="email"
                        v-model="email"
                        required />
                </div>
                <div class="mt-4">
                    <div v-if="errors.password">
                        <label
                            v-for="error in errors.password"
                            :key="error"
                            for="password"
                            class="text-xs mx-1 text-red-500 font-semibold"
                            >{{ error }}</label
                        >
                    </div>
                    <input
                        :placeholder="$t('auth.newPasswordPlaceholder')"
                        id="password"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        v-model="password"
                        required />
                </div>
                <div class="mt-4">
                    <div v-if="errors.password_confirmation">
                        <label
                            v-for="error in errors.password_confirmation"
                            :key="error"
                            for="email"
                            class="text-xs mx-1 text-red-500 font-semibold"
                            >{{ error }}</label
                        >
                    </div>
                    <input
                        :placeholder="$t('auth.confirmNewPassword')"
                        id="password_confirmation"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        v-model="password_confirmation"
                        required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button
                        @click="sendResetlink()"
                        class="text-blue-500 font-semibold">
                        {{ $t('auth.resetPassword') }}
                    </button>
                </div>
            </div>
        </div>
        <approved-modal
            :message="$t('auth.passwordWasChanged')"
            v-if="status === 'success'" />
    </div>
</template>

<script>
import approvedModal from '../recipe/approvedModal'

export default {
    components: {
        approvedModal,
    },
    props: { token: String },
    data: function () {
        return {
            errors: { password: null, confirm_password: null },
            email: '',
            password: '',
            password_confirmation: '',
            status: null,
        }
    },
    methods: {
        sendResetlink() {
            axios
                .post(`${this.$app_url}reset-password`, {
                    email: this.email,
                    token: this.token,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                .then((res) => {
                    this.status = res.data.status
                    if (res.data.status === 'success') {
                        setTimeout(() => {
                            window.location.href = '/'
                        }, 4000)
                    }
                })
                .catch((err) => {
                    console.log(err)
                    this.errors = {
                        password: err.response.data.errors.password,
                        confirm_password:
                            err.response.data.errors.confirm_password,
                    }
                })
        },
    },
}
</script>

<template>
    <div>
        <modal @closeModal="$emit('close')">
            <div
                class="mx-2 justify-center w-full sm:w-3/4 md:w-2/4 lg:w-2/6 p-4 rounded-lg bg-white relative">
                <div
                    v-if="success"
                    class="z-10 absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center bg-white shadow-md rounded-lg">
                    <i
                        class="far fa-check-circle custom-size text-blue-500"></i>
                    <p>{{ $t('settings.passwordWasChanged') }}</p>
                </div>
                <h2 class="px-3 text-base font-semibold text-blue-500">
                    {{ $t('settings.passwordChange') }}
                </h2>
                <div class="mt-4 relative">
                    <div
                        class="absolute left-3 -top-4 leading-3"
                        v-if="errors.current_password">
                        <label
                            v-for="error in errors.current_password"
                            :key="error"
                            for="current_password"
                            class="text-xs leading-3 text-red-500 font-semibold"
                            >{{ error }}<br
                        /></label>
                    </div>

                    <input
                        @click="errors.current_password = ''"
                        v-model="currentPassword"
                        :placeholder="$t('settings.currentPasswordPlaceholder')"
                        id="current_password"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        required />
                </div>
                <div class="mt-4 relative">
                    <div
                        class="absolute left-3 -top-4 leading-3"
                        v-if="errors.new_password">
                        <label
                            v-for="error in errors.new_password"
                            :key="error"
                            for="new_password"
                            class="text-xs leading-3 text-red-500 font-semibold"
                            >{{ error }}<br
                        /></label>
                    </div>

                    <input
                        @click="errors.new_password = ''"
                        v-model="newPassword"
                        :placeholder="$t('settings.newPasswordPlaceholder')"
                        id="new_password"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        required />
                </div>

                <div class="mt-4 relative">
                    <div
                        class="absolute left-3 -top-4 leading-3"
                        v-if="errors.new_password_confirmation">
                        <label
                            v-for="error in errors.new_password_confirmation"
                            :key="error"
                            for="password_confirmation"
                            class="text-xs leading-3 text-red-500 font-semibold"
                            >{{ error }} <br
                        /></label>
                    </div>

                    <input
                        @click="errors.password_confirmation = ''"
                        v-model="passwordConfirmation"
                        :placeholder="
                            $t('settings.confirmNewPasswordPlaceholder')
                        "
                        id="password_confirmation"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        required />
                    <div class="flex items-center justify-end mt-4">
                        <button
                            @click="updatePassword()"
                            class="text-blue-500 font-semibold">
                            {{ $t('settings.passwordChangeButton') }}
                        </button>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import modal from '../basicComponents/modal.vue'
export default {
    components: {
        modal,
    },
    data: function () {
        return {
            currentPassword: '',
            newPassword: '',
            passwordConfirmation: '',
            errors: [],
            success: false,
        }
    },
    methods: {
        updatePassword() {
            axios
                .put(`${this.$app_url}update-password`, {
                    current_password: this.currentPassword,
                    new_password: this.newPassword,
                    new_password_confirmation: this.passwordConfirmation,
                })
                .then((res) => {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        this.$emit('close')
                    }, 3000)
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                })
        },
    },
}
</script>

<style lang="scss">
.custom-size {
    font-size: 6rem;
}
</style>

<template>
    <div>
        <div class="flex items-center h-screen justify-center w-full">
            <div class="mx-2 w-full sm:w-2/4 md:w-1/4 p-4">
                <figure
                    class="flex items-center justify-center overflow-hidden w-full max-h-32">
                    <img
                        class="w-44"
                        :src="$app_url + 'storage/images/logo/2.svg'"
                        alt="" />
                </figure>
                <div class="mb-4 text-sm text-gray-600">
                    {{ $t('auth.confirmWithPassword') }}
                </div>
                <div class="mt-4">
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
                        :placeholder="$t('auth.confirmPassword')"
                        id="password"
                        class="block w-full p-2 border-2 rounded-lg border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password" />
                </div>
                <div
                    class="flex justify-end mt-4 text-xl text-blue-500 font-semibold">
                    <button @click="passwordCheck()">
                        {{ $t('global.confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return { password: '', errors: null }
    },
    methods: {
        passwordCheck() {
            axios
                .post(`${this.$app_url}confirm-password`, {
                    password: this.password,
                })
                .then((res) => {
                    window.location.href = res.data.link
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                })
        },
    },
}
</script>

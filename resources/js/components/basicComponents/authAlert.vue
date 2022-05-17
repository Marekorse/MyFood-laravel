<template>
    <div
        class="flex items-center justify-center px-6 py-6 flex-wrap rounded-xl m-1 shadow-md w-full md:w-4/5 lg:w-3/6 xl:w-2/6 overflow-y-auto bg-gray-50">
        <a class="w-16 h-16" :href="$app_url + 'prihlasenie'">
            <img
                :src="$app_url + 'storage/images/logo/cupCake.svg'"
                alt=""
                srcset=""
        /></a>
        <p>
            {{ $t('global.authenticationRequired') }}
        </p>
        <span class="w-full text-center">
            <a
                class="font-semibold text-blue-400"
                :href="$app_url + 'prihlasenie'"
                >{{ $t('nav.login.loginButton') }}</a
            >
        </span>
    </div>
</template>

<script>
import Logo from '../navigation/logo.vue'
export default {
    components: {
        Logo,
    },
}
</script>

<style lang="scss" scoped></style>

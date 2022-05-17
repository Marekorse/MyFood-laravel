<template>
    <div class="relative">
        <a v-if="link" href="/"
            ><img
                class=""
                :src="$app_url + 'storage/images/logo/2.svg'"
                alt=""
                srcset=""
        /></a>
        <img
            v-if="!link"
            class=""
            :src="$app_url + 'storage/images/logo/2.svg'"
            alt=""
            srcset="" />
    </div>
</template>

<script>
export default {
    props: {
        link: Boolean,
    },
}
</script>

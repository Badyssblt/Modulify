<template>
</template>

<script setup>
import cookie from "js-cookie";
import { useAuth } from "@/store/auth";

const route = useRoute();
const { query } = route;
const store = useAuth();


onMounted(() => {
    const success = query?.success;

    if(success) {
        store.token = cookie.get('token');
        store.authenticate({
            email: cookie.get('github_email') ? cookie.get('github_email') : '',
            profilePicture: cookie.get('github_profile_image') ? cookie.get('github_profile_image') : '',
            githubToken: cookie.get('github_token') ? cookie.get('github_token') : '',
            name: cookie.get('github_username') ? cookie.get('github_username') : ''
        });
        navigateTo('/');
    }else {
        navigateTo('/login')
    }

});

</script>

<style>

</style>
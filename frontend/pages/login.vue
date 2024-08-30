<template>
<div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-96 left-2">
    </div>
      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-44 left-72">
      </div>
      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-72 right-0">
      </div>
<div class="bg-secondary/40 py-6 backdrop-blur-md mx-4 px-4 flex flex-col rounded-md md:flex-row md:justify-between md:w-7/12 md:gap-8 md:m-0 md:m-auto md:px-8">
    
    <form @submit.prevent="handleSubmit" class="md:w-full">
        <h2 class="text-lg md:text-xl font-medium">Bon retour</h2>
        <p class="my-2 opacity-60 text-sm">Veuillez entrer vos informations pour vous connecter</p>
        <Input label="Email" placeholder="johndoe@example.com" v-model="email" type="email"/>
        <Input label="Mot de passe" placeholder="********" type="password" class="mt-4" v-model="password"/>
        <div class="flex justify-end mt-2">
            <NuxtLink class="underline text-white/50 text-sm">Mot de passe oublié</NuxtLink>
        </div>
        <Button class="w-full mt-4">Se connecter</Button>
        <div class="flex justify-center mt-4">
            <NuxtLink :to="$config.public.API_URL + '/auth/github'">
            <div class="w-8 h-8 bg-white rounded-full flex justify-center items-center">
                
                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3 .3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5 .3-6.2 2.3zm44.2-1.7c-2.9 .7-4.9 2.6-4.6 4.9 .3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3 .7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3 .3 2.9 2.3 3.9 1.6 1 3.6 .7 4.3-.7 .7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3 .7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3 .7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>
            </div>
            </NuxtLink>
        </div>
        <NuxtLink to="/register" class="text-sm opacity-70 underline text-center w-full flex justify-center mt-4">Ou s'inscrire</NuxtLink>
        
        <Error class="mt-4" v-if="error">Email ou mot de passe invalide</Error>
    </form>
    <div class="bg-background w-full h-96 py-8 mt-4 rounded-md relative px-4 md:w-fit">
        <div class="absolute w-16 h-16 bg-primary rounded-full blur-[45px] bottom-12 left-12">
      </div>
      <div class="absolute w-16 h-16 bg-[#16ADAD] rounded-full blur-[45px] top-32 right-12">
      </div>
        <p class="italic opacity-70 text-center max-w-xl min-w-72"><span class="font-bold">"</span>{{ quote[0]['q'] }}<span class="font-bold">"</span></p>
        <h2 class="text-lg text-right">{{ quote[0]['a'] }}</h2>
    </div>

</div>

</template>

<script setup>
import cookie from "js-cookie";
import { useAuth } from "@/store/auth"
import { jwtDecode } from "jwt-decode";
import axios from 'axios';

    const store = useAuth();
    const { $api } = useNuxtApp();
    const email = ref('');
    const password = ref('');
    const error = ref(false);

    const config = useRuntimeConfig();

    const handleSubmit = async () => {
        try {
            const response = await $api.post('/api/login_check', {
                username: email.value,
                password: password.value
            });
            if(response.data.token){

                const decoded = jwtDecode(response.data.token);
                // Supprime une possible connexion avec github des cookies
                cookie.remove('github_profile_image')
                cookie.remove('github_email')
                cookie.remove('github_token')
                cookie.remove('github_username')

                store.authenticate({
                    email: decoded.username,
                    name: decoded.name
                })

                // Défini la connexion par défaut
                store.token = response.data.token;
                navigateTo('/');
            }
        } catch (err) {
            error.value = true;
            console.log(error.value);
        }
    }


const { data:quote } = await useFetch(config.public.API_URL + "/api/quote");


</script>

<style>

</style>
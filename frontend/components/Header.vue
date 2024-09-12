<template>
    <header class="py-6 flex justify-between px-6 md:px-12 border-b border-b-gray-600">
        <NuxtLink to="/"><h1 class="text-lg">Modulify</h1></NuxtLink>
        <div v-if="showMenu" class="fixed top-0 right-0 w-full h-screen bg-background z-50 md:hidden" style="z-index: 300">
            <div class="relative h-full">
                <div class="flex flex-col justify-center h-full items-center">
                    <NuxtLink to="/assets" @click="toggleMenu">Tous les assets</NuxtLink>
                    <NuxtLink to="/login" @click="toggleMenu">Se connecter</NuxtLink>
                </div>
                <div class="absolute right-6 top-6">
                    <button @click="toggleMenu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="justify-center h-full items-center hidden md:flex gap-12">
            <NuxtLink to="/assets" @click="toggleMenu">Tous les assets</NuxtLink>
            <NuxtLink to="/login" @click="toggleMenu" v-if="!isAuthenticated">Se connecter</NuxtLink>
        </div>
        <div class="flex gap-4">
            <NuxtLink to="/dashboard">
                <div class="w-8" v-if="isAuthenticated">
                    <div class="w-full h-8 relative overflow-hidden">
                            <img :src="store.user && store.user.profilePicture ? store.user.profilePicture : 'images/default.jpg'" alt="" class="absolute inset-0 w-full h-full object-cover rounded-full">
                    </div> 
                </div>
            </NuxtLink>
            
            <button @click="toggleMenu" class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                </svg>
            </button>
        </div>
    </header>
</template>

<script setup>
import { useAuth } from "@/store/auth.js"
    import cookie from "js-cookie";

    const store = useAuth();

    const isAuthenticated = ref(store.isAuthenticated);

    const showMenu = ref(false);

    const toggleMenu = () => {
        showMenu.value = !showMenu.value;
    }

    onMounted(() => {

    })

watch(
    () => store.isAuthenticated,
    (newVal) => {
        isAuthenticated.value = newVal;
    }
);
</script>

<style>

</style>
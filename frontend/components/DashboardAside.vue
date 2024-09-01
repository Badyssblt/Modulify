<template>
        <aside class="md:w-fit md:px-6 md:border-r-2 border-secondary">
            <div class="flex flex-col gap-4">
                <NuxtLink to="/dashboard" :class="['py-2 text-nowrap rounded-full flex justify-center', current === 'profile' ? 'bg-primary' : 'bg-secondary' ]">Mon profil</NuxtLink>
                <NuxtLink to="/dashboard/assets" :class="['py-2 px-8 text-nowrap rounded-full flex justify-center', current === 'assets' ? 'bg-primary' : 'bg-secondary' ]">Mes assets suivies</NuxtLink>
                <button :class="['py-2 px-8 text-nowrap rounded-full', current === 'confidential' ? 'bg-primary' : 'bg-secondary' ]">Confidentialité</button>
            </div>
            <div class="mt-4">
                <button class="w-full flex justify-center gap-4" @click="logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Se déconnecter
                </button>
            </div>
        </aside>
</template>

<script setup>
import { useAuth } from "@/store/auth"

    defineProps({
        current: {
            type: String,
            default: "profile"
        }
    })

    const store = useAuth();

    const logout = () => {
        store.logout();
        navigateTo('/')
    }
</script>

<style>

</style>
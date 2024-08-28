<template>
    <div class="md:flex px-6 md:px-12">
        <aside class="md:w-fit md:px-6">
            <div class="flex flex-col gap-4">
                <NuxtLink :class="['py-2 text-nowrap flex justify-center' ]">Assets</NuxtLink>
                <NuxtLink class="bg-secondary py-2 px-8 text-nowrap flex justify-center">Utilisateurs</NuxtLink>
                <NuxtLink class="bg-secondary py-2 px-8 text-nowrap flex justify-center">Catégories</NuxtLink>
            </div>
        </aside>
        <div class="bg-secondary w-full p-4 rounded-md ml-4">
            <div>
                <div class="flex justify-between">
                    <h2 class="textlg md:text-xl">Tous les assets</h2>
                    <div>
                        <Navlink class="bg-primary flex px-4 py-2 justify-center items-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Ajouter un asset</Navlink>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-md border-2 border-background mt-4" style="border-color: rgb(55 65 81);">
                <table class="w-full px-4">
                    <thead >
                        <tr>
                        <th class=" p-4">ID</th>
                        <th class=" p-4">Nom</th>
                        <th class=" p-4">Prix</th>
                        <th class=" p-4">Description</th>
                        <th class=" p-4">Créer le</th>
                        <th class=" p-4">Visibilité</th>
                        <th class="p-4">Nombre total de téléchargement</th>
                        <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asset in assets" :key="asset.id" class="border-b" style="border-color: rgb(55 65 81);">
                        <td class=" p-4">{{ asset.id }}</td>
                        <td class=" p-4">{{ asset.name }}</td>
                        <td class=" p-4">{{ asset.price }}</td>
                        <td class=" p-4">{{ asset.description }}</td>
                        <td class=" p-4">{{ asset.created_at }}</td>
                        <td class=" p-4">{{ asset.is_public == 1 ? 'Public' : 'Privée' }}</td>
                        <td class=" p-4">{{ asset.total_download }}</td>
                        <td class="p-4">
                            <div class="flex justify-between items-center h-full ">
                                <button>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button type="button" @click="deleteAsset(asset.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>  
</template>

<script setup>
import { useAuth } from "@/store/auth";

    definePageMeta({
        middleware: 'is-admin'
    })

    const { $api } = useNuxtApp();
    const config = useRuntimeConfig();
    const store = useAuth();
    const token = store.token;
    const assets = ref([]);

    const fetchAssets = async() => {
        try {
            const response = await $api.get('/api/admin/assets');
            assets.value = response.data;
        } catch (error) {
            
        }
    }

    await fetchAssets()

    const deleteAsset = async (id) => {
        try {
            const response = await $api.delete('/api/asset/' + id);
            fetchAssets();
        } catch (error) {
            
        }
    }
</script>

<style>

</style>
<template>
    <div >
        <div class="px-6 md:flex gap-32 md:px-12 md:pt-24">
            <div class="md:w-1/2">
                <h2 class="text-lg font-medium md:text-xl">{{ asset.name }}</h2>
                <p class="text-white/60 text-sm">{{ asset.description }}</p>
                <div>
                    <h3 class="mt-4 text-lg md:text-xl font-bold">{{ asset.price === 0 ? 'Gratuit' : asset.price + ' €' }}</h3>
                    <Button class="mt-4 w-full" @click="downloadFile" :state="loading">{{ asset.price === 0 ? 'Télécharger' : 'Acheter' }}</Button>
                </div>
            </div>

            <div class="bg-secondary p-4 rounded-md -mb-8 relative z-50 mt-4 md:w-full md:max-w-3xl">
                <div class="w-full h-48 md:h-96 relative overflow-hidden ">
                    <img :src="$config.public.API_URL + '/images/asset/' + asset.imageName" alt="" class="absolute inset-0 w-full h-full object-cover rounded-t-md">
                </div>
            </div>
        </div>

        <div class="bg-secondary/70 py-20">
            <div class="px-6 border-b pb-14 md:flex md:justify-between">
                <h2 class="text-lg md:text-xl">Comment utiliser cet asset ?</h2>
                <p class="text-sm text-white/60 md:w-1/3 md:mr-24">{{ asset.how }}</p>
            </div>
           <div class="px-6 border-b py-14 md:flex md:justify-between">
                <h2 class="text-lg md:text-xl">Version de cet asset</h2>
                <p class="text-sm text-white/60 md:w-1/3 md:mr-24">{{ asset.version }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>

    const route = useRoute();
    const config = useRuntimeConfig();
    const loading = ref(false);

    const { data: asset } = await useFetch(config.public.API_URL + '/api/asset/' + route.params.id);

    const { $api } = useNuxtApp();

const downloadFile = async () => {
  try {
    loading.value = true

    // Appel à l'API pour télécharger le fichier
    const response = await $api.get('/api/download/' + asset.value.file, {
      responseType: 'blob', // Spécifie que la réponse est un blob
    })

    // Créer un objet URL pour le blob
    const url = window.URL.createObjectURL(new Blob([response.data]))

    // Créer un lien temporaire pour déclencher le téléchargement
    const link = document.createElement('a')
    link.href = url

    // Définir le nom du fichier à télécharger
    link.setAttribute('download', asset.value.file)

    // Ajouter le lien au document et simuler un clic
    document.body.appendChild(link)
    link.click()

    // Nettoyer l'URL de l'objet et supprimer le lien
    window.URL.revokeObjectURL(url)
    document.body.removeChild(link)
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}
</script>

<style>

</style>

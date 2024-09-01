<template>
    <div >
        <div class="px-6 md:flex gap-32 md:px-12 md:pt-24">
            <div class="md:w-1/2">
                <h2 class="text-lg font-medium md:text-xl">{{ asset.name }}</h2>
                <p class="text-white/60 text-sm">{{ asset.description }}</p>
                <div>
                    <h3 class="mt-4 text-lg md:text-xl font-bold">{{ asset.price === 0 ? 'Gratuit' : asset.price + ' €' }}</h3>
                    <Button class="mt-4 w-full" @click="downloadFile" :state="loading">{{ asset.price === 0 ? 'Télécharger' : 'Acheter' }}</Button>
                    <Button class="mt-4 w-full !bg-background border-2 border-primary " :state="loading" @click="uploadGitHub">{{ asset.price === 0 ? 'Importer sur Github' : 'Acheter' }}</Button>
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
import { useAuth } from '@/store/auth';

    const route = useRoute();
    const config = useRuntimeConfig();
    const loading = ref(false);
    const store = useAuth();

    const { data: asset } = await useFetch(config.public.API_URL + '/api/asset/' + route.params.id);

    const { $api } = useNuxtApp();

const downloadFile = async () => {
  try {
    loading.value = true

    const response = await $api.get('/api/download/' + asset.value.file, {
      responseType: 'blob', 
    })

    const url = window.URL.createObjectURL(new Blob([response.data]))

    const link = document.createElement('a')
    link.href = url

    link.setAttribute('download', asset.value.file)

    document.body.appendChild(link)
    link.click()

    window.URL.revokeObjectURL(url)
    document.body.removeChild(link)
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}


const uploadGitHub = async () => {
  try {
    loading.value = true;

    const githubToken = store.user.githubToken; 
    const owner = store.user.name; 
    const repo = "SAE202"; 
    const path = asset.value.file; 

    const response = await $api.get('/api/download/' + asset.value.file, {
      responseType: 'blob',
    });

    const reader = new FileReader();
    reader.readAsDataURL(response.data);
    reader.onloadend = async () => {
      const base64File = reader.result.split(',')[1]; 

      const checkResponse = await fetch(`https://api.github.com/repos/${owner}/${repo}/contents/${path}`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${githubToken}`,
          'Content-Type': 'application/json',
        },
      });

      let sha = null; 

      if (checkResponse.ok) {
        const fileData = await checkResponse.json();
        sha = fileData.sha; 
      }

      const githubResponse = await fetch(`https://api.github.com/repos/${owner}/${repo}/contents/${path}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${githubToken}`, 
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          message: 'Commit depuis Modulify',
          content: base64File,
          sha: sha, 
        }),
      });

      const responseData = await githubResponse.json();

      if (!githubResponse.ok) {
        console.error('GitHub API Error:', responseData);
        throw new Error('Failed to upload file to GitHub');
      }

      console.log('File successfully uploaded to GitHub');
    };
  } catch (error) {
    console.error('Error uploading to GitHub:', error);
  } finally {
    loading.value = false;
  }
};

</script>

<style>

</style>

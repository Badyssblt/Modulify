<template>
  <div
    class="fixed left-1/2 transform -translate-x-1/2 bg-secondary w-1/2 rounded-md p-4 h-96 overflow-auto"
    style="z-index: 999"
  >
    <div class="relative">
      <div class="absolute right-4">
        <button @click="$emit('close')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
        </button>
      </div>
      <div class="flex flex-col">
        <div class="border-b py-4" v-for="item in repository" :key="item.name">
          <h2 @click="seeContent(item.name, '')">{{ item.name }}</h2>
          <ul v-if="content[item.name]" class="ml-4">
            <button
              class="bg-primary px-4 rounded-full py-1 my-2"
              @click="setPath(currentPath[item.name], item.name)"
            >
              Importer ici
            </button>
            <li
              v-for="file in content[item.name]"
              :key="file.path"
              @click="file.type === 'dir' ? seeContent(item.name, file.path) : null"
            >
              <span v-if="file.type === 'dir'">📁 {{ file.name }}</span>
              <span v-else>📄 {{ file.name }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { useAuth } from '@/store/auth';
import { useGit } from '@/store/git';

const props = defineProps(['repository', 'asset']);
const emits = defineEmits(['close']);

const content = ref({});
const currentPath = ref({});
const store = useAuth();
const gitStore = useGit();
const asset = ref(props.asset);

const loading = ref(false);
const { $api } = useNuxtApp();

const githubToken = store.user.githubToken;


const seeContent = async (repoName, path) => {
  try {
    const response = await axios.get(
      `https://api.github.com/repos/${store.user.name}/${repoName}/contents/${path}`,
      {
        headers: {
          Authorization: `token ${githubToken}`
        }
      }
    );
    content.value[repoName] = response.data;

    currentPath.value[repoName] = path;

  } catch (error) {
    console.error('Erreur lors de la récupération du contenu du dépôt :', error);
  }
};

const setPath = (path, repoName) => {
  gitStore.setPath(path);
  gitStore.setRepository(repoName);
  uploadGitHub(gitStore.path, gitStore.repository);

};

const uploadGitHub = async (path, repo) => {
  try {
    loading.value = true;

    const githubToken = store.user.githubToken; 
    const owner = store.user.name; 

    // Récupération du fichier à uploader
    const response = await $api.get(`/api/download/${asset.value.file}`, {
      responseType: 'blob',
    });

    // Lecture du fichier en base64
    const reader = new FileReader();
    reader.readAsDataURL(response.data);
    reader.onloadend = async () => {
      const base64File = reader.result.split(',')[1]; 

      // Vérifie si le fichier existe déjà
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

      const githubResponse = await fetch(`https://api.github.com/repos/${owner}/${repo}/contents/${path}/${asset.value.file}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${githubToken}`, 
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          message: 'Commit depuis Modulify',
          content: base64File,
          ...(sha ? { sha } : {}), 
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

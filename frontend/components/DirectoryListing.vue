<template>
  <div class="absolute bg-white/20 blur-2xl w-full h-full top-0 " style="z-index: 200">
  </div>
  <div class="fixed left-1/2 transform -translate-x-1/2 bg-secondary w-8/12 rounded-md p-4 h-2/3 overflow-auto"
       style="z-index: 999">
    <div class="relative">
      <div class="absolute right-4">
        <button @click="$emit('close')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
               class="size-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex flex-col">
        <div class="border-b py-4 hover:cursor-pointer" v-for="item in repository" :key="item.name">
          <h2 @click="toggleContent(item.name)">{{ item.name }}</h2>
          <ul v-if="isContentVisible(item.name)" class="ml-4">
            <Button class="bg-primary px-4 rounded-full py-1 my-2 flex justify-center"
                    @click="setPath(currentPath[item.name], item.name)"
                    :state="loading[item.name]">
              Importer ici
              <svg v-if="uploaded[item.name]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                   stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
              </svg>
            </Button>

            <li v-for="file in content[item.name]" :key="file.path"
                @click="file.type === 'dir' ? seeContent(item.name, file.path) : null" v-if="content[item.name]">
              <span v-if="file.type === 'dir'">📁 {{ file.name }}</span>
              <span v-else>📄 {{ file.name }}</span>
            </li>
            <li v-else class="ml-4"><Loader/></li>


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
const visibleContent = ref({});
const store = useAuth();
const gitStore = useGit();
const asset = ref(props.asset);

const loading = ref({});
const uploaded = ref({});
const { $api } = useNuxtApp();

const githubToken = store.user.githubToken;

const toggleContent = (repoName) => {
  if (visibleContent.value[repoName]) {
    visibleContent.value[repoName] = false;
  } else {
    seeContent(repoName, '');
    visibleContent.value[repoName] = true;
  }
};

const isContentVisible = (repoName) => {
  return visibleContent.value[repoName] || false;
};

const seeContent = async (repoName, path) => {
  try {
    const response = await axios.get(
        `https://api.github.com/repos/${store.user.name}/${repoName}/contents/${path}`,
        {
          headers: {
            Authorization: `token ${githubToken}`,
          },
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

  uploadGitHub(gitStore.path, gitStore.repository, repoName);
};

const uploadGitHub = async (path, repo, repoName) => {
  try {
    loading.value[repoName] = true;

    const githubToken = store.user.githubToken;
    const owner = store.user.name;

    const response = await $api.get(`/api/download/${asset.value.file}`, {
      responseType: 'blob',
    });

    const reader = new FileReader();
    reader.readAsDataURL(response.data);
    reader.onloadend = async () => {
      const base64File = reader.result.split(',')[1];
      console.log(path);
      const checkResponse = await fetch(`https://api.github.com/repos/${owner}/${repo}/contents/${path}`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${githubToken}`,
          'Content-Type': 'application/json',
        },
      });

      let sha = null;

      if (checkResponse.ok) {
        const fileData = await checkResponse.json();
        sha = fileData.sha;
      }
      let githubResponse = null;
      if(path === ""){
        githubResponse = await fetch(
            `https://api.github.com/repos/${owner}/${repo}/contents/${asset.value.file}`,
            {
              method: 'PUT',
              headers: {
                Authorization: `Bearer ${githubToken}`,
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                message: 'Commit depuis Modulify',
                content: base64File,
                ...(sha ? { sha } : {}),
              }),
            }
        );
      }else {
        githubResponse = await fetch(
            `https://api.github.com/repos/${owner}/${repo}/contents/${path}/${asset.value.file}`,
            {
              method: 'PUT',
              headers: {
                Authorization: `Bearer ${githubToken}`,
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                message: 'Commit depuis Modulify',
                content: base64File,
                ...(sha ? { sha } : {}),
              }),
            }
        );
      }


      const responseData = await githubResponse.json();

      if (!githubResponse.ok) {
        console.error('GitHub API Error:', responseData);
        throw new Error('Failed to upload file to GitHub');
      }

      uploaded.value[repoName] = true;
    };
  } catch (error) {

    console.error('Error uploading to GitHub:', error);
  } finally {
    loading.value[repoName] = false; // Arrête le loader pour ce repo
  }
};
</script>

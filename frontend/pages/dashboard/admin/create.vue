<template>
    <form @submit.prevent="handleSubmit" class="px-6 md:px-12">
        <Input label="Nom" placeholder="Template Symfony" color="secondary" v-model="name" />
        
        <Input label="Prix" type="number" placeholder="Prix" color="secondary" v-model="price" />
        
        <label for="description" class="flex flex-col">Description
            <textarea name="description" id="description" cols="30" rows="10" class="bg-secondary rounded-lg" v-model="description"></textarea>
        </label>

        <label for="how" class="flex flex-col">Comment utiliser cet asset
            <textarea name="how" id="how" cols="30" rows="10" class="bg-secondary rounded-lg" v-model="how"></textarea>
        </label>

        <label for="version" class="flex flex-col">Version de cet asset
            <textarea name="version" id="version" cols="30" rows="10" class="bg-secondary rounded-lg" v-model="version"></textarea>
        </label>
        
        <div class="flex gap-6 mt-4">
            <div>
                <input type="radio" name="visibility" value="public" id="public" v-model="visibility" />
                <label for="public">Public</label>
            </div>
            <div>
                <input type="radio" name="visibility" value="private" id="private" v-model="visibility" />
                <label for="private">Privée</label>
            </div>
        </div>

        <label for="image" class="flex flex-col">Image de l'asset
            <input type="file" id="image" @change="handleImageUpload" />
        </label>
        
        <label for="file" class="flex flex-col">Fichier de l'asset
            <input type="file" id="file" @change="handleFileUpload" />
        </label>

        <Button type="submit" class="btn btn-primary mt-4">Créer l'asset</Button>
    </form>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const price = ref(0);
const description = ref('');
const visibility = ref('private');
const image = ref(null);
const file = ref(null);
const how = ref('');
const version = ref('');

const { $api } = useNuxtApp();

const handleImageUpload = (event) => {
    const selectedFiles = event.target.files;
    if (selectedFiles && selectedFiles.length > 0) {
        image.value = selectedFiles[0]; 
    } else {
        image.value = null; 
    }
};

const handleFileUpload = (event) => {
    const selectedFiles = event.target.files;
    if (selectedFiles && selectedFiles.length > 0) {
        file.value = selectedFiles[0]; 
    } else {
        file.value = null; 
    }
};

const handleSubmit = async () => {
    const form = new FormData();

    form.append('name', name.value);
    form.append('price', price.value);
    form.append('description', description.value);
    form.append('visibility', visibility.value === 'public' ? true : false);
    form.append('how', how.value);
    form.append('version', version.value);

    if (image.value) {
        form.append('image', image.value);
    }

    if (file.value) {
        form.append('file', file.value);
    }

    try {
        const response = await $api.post('/api/asset', form, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        navigateTo('/assets');
    } catch (error) {
        console.error('Error uploading asset:', error);
    }
};
</script>

<template>
        <aside class="md:w-fit md:px-6 md:border-r-2 border-secondary">
            <div class="flex flex-col gap-4">
                <button :class="['py-2 text-nowrap', current === 'profile' ? 'bg-primary' : 'bg-secondary' ]">Mon profil</button>
                <button class="bg-secondary py-2 px-8 text-nowrap">Mes assets suivies</button>
                <button class="bg-secondary py-2 px-8 text-nowrap">Confidentialité</button>
            </div>
        </aside>
</template>

<script setup>
    defineProps({
        current: {
            type: String,
            default: "profile"
        }
    })
</script>

<style>

</style>
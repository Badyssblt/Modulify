<template>
        <aside>
            <div class="flex flex-col gap-4">
                <button :class="['py-2', current === 'profile' ? 'bg-primary' : 'bg-secondary' ]">Mon profil</button>
                <button class="bg-secondary py-2">Mes assets suivies</button>
                <button class="bg-secondary py-2    ">Confidentialité</button>
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
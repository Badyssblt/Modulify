<template>
  <article class="bg-secondary w-full h-fit rounded-md">
    <div class="w-full h-48 relative overflow-hidden">
        <img src="https://placehold.co/600x400" alt="" class="absolute inset-0 w-full h-full object-cover rounded-t-md">
    </div>
    <div class="py-3 px-4">
        <div class="flex justify-between">
            <h2 class="font-medium">{{ asset.name }}</h2>
            <h3 class="font-medium">{{ asset.price === 0 ? 'Gratuit' : asset.price + ' €' }}</h3>
        </div>
        <div class="mt-3">
            <div class="flex items-center gap-2">
                <div class="w-12 h-12 relative overflow-hidden">
                    <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="" class="absolute inset-0 w-full h-full object-cover rounded-full">
                </div>
                <p>John Doe</p>
            </div>
            <div>

            </div>
        </div>
    </div>
    
  </article>
</template>

<script setup>
defineProps({
    asset: {
        type: Object, 
    }
})



</script>

<style>

</style>
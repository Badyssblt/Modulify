
export const useAuth = defineStore('auth', () => {
    const user = ref(null);
    const token = ref('');

    const isAuthenticated = computed(() => user !== null);


    const authenticate = (newUser) => {
        user.value = newUser;
        console.log(user.value);
    }

    return {
        user, 
        authenticate,
        token,
        isAuthenticated
    }
}, { persist: {
    storage: persistedState.cookiesWithOptions({
        sameSite: 'strict'
    })
} });
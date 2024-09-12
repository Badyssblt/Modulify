import { jwtDecode } from "jwt-decode";

export const useAuth = defineStore(
  "auth",
  () => {
    const user = ref(null);
    const token = ref("");

      const isTokenValid = () => {
          if (!token.value) return false;

          try {
              const decodedToken = jwtDecode(token.value);
              const currentTime = Date.now() / 1000;
              return decodedToken.exp > currentTime;
          } catch (e) {
              return false;
          }
      };

      const isAuthenticated = computed(() => {
          return user.value !== null && isTokenValid();
      });

    const authenticate = (newUser) => {
      user.value = newUser;
    };

    const logout = () => {
      user.value = null;
      token.value = null;
    };

    return {
      user,
      authenticate,
      token,
      isAuthenticated,
      logout,
    };
  },
  {
    persist: {
      storage: persistedState.cookiesWithOptions({
        sameSite: "strict",
      }),
    },
  }
);

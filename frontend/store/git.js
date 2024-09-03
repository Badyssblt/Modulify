export const useGit = defineStore(
    "git",
    () => {
        const path = ref('');
        const repository = ref('');
 
        const setPath = (newPath) => {
            path.value = newPath;
        }

        const setRepository = (repo) => {
            repository.value = repo;
        }

        return {
            setPath,
            path,
            setRepository,
            repository
        }
    }
)
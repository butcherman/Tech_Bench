import axios from "axios";
import { defineStore } from "pinia";
import { ref, unref } from "vue";

export const useFileTypeStore = defineStore("fileTypeStore", () => {
    const fileTypes = ref([]);

    /**
     * If the File Types List is empty, fetch it from the server
     */
    const initStore = async () => {
        if (!fileTypes.value.length) {
            await axios.get(route("file-types")).then((res) => {
                fileTypes.value = res.data;
            });
        }
    };

    const getFileTypes = () => {
        initStore();

        return unref(fileTypes);
    };

    return {
        getFileTypes,
    };
});

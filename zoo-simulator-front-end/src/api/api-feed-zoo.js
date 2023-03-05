import axios from "axios";

const feedZoo = async () => {
    const response = await axios.put(
        `${import.meta.env.VITE_BASE_URL}/api/feed`
    );
    return response;
};

export default feedZoo;

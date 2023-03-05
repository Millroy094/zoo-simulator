import axios from "axios";

const createZoo = async () => {
    const response = await axios.post(
        `${import.meta.env.VITE_BASE_URL}/api/create`
    );
    return response;
};

export default createZoo;

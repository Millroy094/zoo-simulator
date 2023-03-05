import axios from "axios";

const deleteZoo = async () => {
    const response = await axios.delete(
        `${import.meta.env.VITE_BASE_URL}/api/zoo/destroy`
    );
    return response;
};

export default deleteZoo;

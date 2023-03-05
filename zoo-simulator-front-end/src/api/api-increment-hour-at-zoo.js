import axios from "axios";

const incrementHourAtZoo = async () => {
    const response = await axios.put(
        `${import.meta.env.VITE_BASE_URL}/api/zoo/increment-hour`
    );
    return response;
};

export default incrementHourAtZoo;

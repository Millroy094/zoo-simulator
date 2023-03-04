import axios from "axios";

const incrementHourAtZoo = async () => {
    const response = await axios.put(
        "http://localhost:8000/api/increment-hour"
    );
    return response;
};

export default incrementHourAtZoo;

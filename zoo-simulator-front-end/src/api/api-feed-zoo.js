import axios from "axios";

const feedZoo = async () => {
    const response = await axios.put("http://localhost:8000/api/feed");
    return response;
};

export default feedZoo;

import axios from "axios";

const createZoo = async () => {
    const response = await axios.post("http://localhost:8000/api/create");
    return response;
};

export default createZoo;

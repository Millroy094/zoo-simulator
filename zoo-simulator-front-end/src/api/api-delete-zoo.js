import axios from "axios";

const deleteZoo = async () => {
    const response = await axios.delete("http://localhost:8000/api/destroy");
    return response;
};

export default deleteZoo;

import axios from "./axios.config";

export const getPosts = () => {
    return axios.get("/posts");
};

export const getPostById = (id : number) =>{
    return axios.get("/posts/"+id);
}

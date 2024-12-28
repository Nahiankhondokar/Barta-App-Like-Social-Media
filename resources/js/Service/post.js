import axios from "axios";
import { unAuthenticateUser } from "../middleware/authentication";


export const getAllPost = async (limit = 5) => {
    return await axios
        .get("/api/post")
        .then((response) => response.data)
        .catch(function (error) {
            unAuthenticateUser(error.status);
        });
};
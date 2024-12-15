import axios from "axios";
import { unAuthenticateUser } from "../middleware/authentication";


export const getAllPost = async () => {
    return await axios
        .get("/api/post")
        .then((response) => response.data.data)
        .catch(function (error) {
            unAuthenticateUser(error.status);
        });
};
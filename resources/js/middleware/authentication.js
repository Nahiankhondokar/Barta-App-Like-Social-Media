
import router from "@/router";
import {ref} from "vue";

export const unAuthenticateUser = (statusCode) => {
   if(statusCode == 401){
        localStorage.removeItem("loggedIn");
        localStorage.removeItem("loggedInUser");
        router.push({ name: "Login" });

        axios
        .get("/api/logout")
        .then(function (response) {
            // console.log('logout')
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
   }
}

export const authUser = ref({});
export const authenticationCheck = async ()=> {
    authUser.value = {};
    return await axios
        .get("/api/me")
        .then((response)=> {
            authUser.value = response.data.data;
        }).catch((error) => {
            unAuthenticateUser(error.status);
        });
}
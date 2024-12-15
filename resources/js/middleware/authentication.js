
import router from "@/router";
import {ref} from "vue";

export const unAuthenticateUser = (statusCode) => {
   if(statusCode == 401){
        localStorage.removeItem("loggedIn");
        router.push({ name: "Login" });

        axios
        .get("/api/logout")
        .then(function (response) {
        //
        })
        .catch(function (error) {
            console.log(error);
        });
   }
}

export const authUser = ref({});
export const authenticationCheck = async ()=> {
    return await axios
        .get("/api/me")
        .then((response)=> {
            authUser.value = response.data.data;
        }).catch((error) => {
            unAuthenticateUser(error.status);
        });
}
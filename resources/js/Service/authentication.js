
import router from "@/router";

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


export const authenticationCheck = async ()=> {
    return await axios
        .get("/api/me")
        .then((response)=> response.data)
        .catch(function (error) {
            unAuthenticateUser(error.status);
        });
}
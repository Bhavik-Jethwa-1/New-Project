<template>
    <div
        class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
    >
        <div
            class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0"
        >
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1
                    class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl"
                >
                    Login your account
                </h1>
                <div class="space-y-4 md:space-y-6">
                    <div>
                        <label
                            for="mobile_number"
                            class="block mb-2 text-sm font-medium text-gray-900"
                            >Mobile Number</label
                        >
                        <input
                            type="text"
                            v-model="mobile_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Enter Your Number"
                        />
                    </div>
                    <div>
                        <label
                            for="password"
                            class="block mb-2 text-sm font-medium text-gray-900"
                            >Password</label
                        >
                        <input
                            type="password"
                            v-model="password"
                            name="password"
                            id="password"
                            placeholder="Enter Your Password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                        />
                    </div>
                    <button
                        @click="Login"
                        class="w-full cursor-pointer text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    >
                        Login
                    </button>
                    <router-link
                        to="/register"
                        class="flex justify-end font-medium text-blue-600 hover:underline"
                        >Register here</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from './store/auth';

export default {
    name: "login",
    data() {
        return {
            mobile_number: "",
            password: "",
        };
    },
    created() {
        const token = localStorage.getItem("token");
        if (token) {
            this.$router.push({ name: "DefaultLayout" });
        }
    },
//     methods: {
//     async Login() {
//         await axios
//             .post("/api/login", {
//                 mobile_number: this.mobile_number,
//                 password: this.password,
//             })
//             .then(({ data }) => {
//                 if (data && data.message === "Login successful") {
//                     localStorage.setItem("token", data.token);
//                     localStorage.setItem("user", JSON.stringify(data.user));

//                     // Check user role and redirect accordingly
//                     const user = JSON.parse(localStorage.getItem('user'));
//                     if (user.role === 'volunteer') {
//                         this.$router.push({ name: "UserPersonDetails" });
//                     } else {
//                         this.$router.push({ name: "userInfo" }); // or any other route for non-volunteers
//                     }
//                 }
//             })
//             .catch(({ response }) => {
//                 if (response !== undefined) {
//                     const { status, data } = response;
//                     if (status === 422) {
//                         this.displayError(data);
//                     } else {
//                         this.$toast.error(data.message);
//                     }
//                 }
//             })
//             .finally(() => {});
//     },
// }


  methods: {
    async Login() {
      const auth = useAuthStore()
      try {
        const response = await axios.post('/api/login', {
          mobile_number: this.mobile_number,
          password: this.password
        })

        if (response.data && response.data.token) {
          auth.setAuth(response.data)

          if (response.data.role === 'volunteer') {
            this.$router.push({ name: 'DefaultLayout' })
          } else {
            this.$router.push({ name: 'Login' })
          }
        }
      } catch (error) {
        if (error.response) {
          this.$toast.error(error.response.data.message)
        }
      }
    }
  }
}


    // methods: {
    //     async Login() {
    //         await this.$axios
    //             .post("/api/login", {
    //                 mobile_number: this.mobile_number,
    //                 password: this.password,
    //             })
    //             .then(({ data }) => {
    //                 if (data && data.message === "Login successful") {
    //                     localStorage.setItem("token", data.token);
    //                     localStorage.setItem("user", JSON.stringify(data.user));
    //                     this.$router.push({ name: "UserPersonDetails" });
    //                 }
    //             })
    //             .catch(({ response }) => {
    //                 if (response !== undefined) {
    //                     const { status, data } = response;
    //                     if (status === 422) {
    //                         this.displayError(data);
    //                     } else {
    //                         this.$toast.error(data.message);
    //                     }
    //                 }
    //             })
    //             .finally(() => {});
    //     },
    // },
// };
</script>

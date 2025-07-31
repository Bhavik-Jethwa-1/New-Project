// export default [
//     {
//         name : 'Login',
//         path : '/login',
//         component : () => import('@/Login.vue')
//     },
//      {
//         name : 'Register',
//         path : '/register',
//         component : () => import('@/Register.vue')
//     }
// ]

import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('@/Login.vue'),
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('@/Register.vue'),
    },
    {
        path: '/',
        name: 'DefaultLayout',
        component: () => import('@/layout/DefaultLayout.vue'),
        children: [
            {
                path: '/volunteerFamily',
                name: 'VolunteerFamily',
                component: () => import('@/component/volunteer/VolunteerFamily.vue'),
            },
            {
                path: '/volunteer',
                name: 'Volunteer',
                component: () => import('@/component/volunteer/Volunteer.vue'),
            },
            {
                path: '/volunteerDetail',
                name: 'VolunteerDetail',
                component: () => import('@/component/volunteer/VolunteerDetail.vue'),
                meta: { isAuthenticated: true }
            },
            {
                path: '/personDetail',
                name: 'PersonDetail',
                component: () => import('@/component/user/PersonDetail.vue'),
                meta: { isAuthenticated: true }
            },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router


import React from 'react';
import Routes from './Routes';
const Login = React.lazy(() => import('../../admin/components/auth/Login'));
const ForgetPassword = React.lazy(() => import('../../admin/components/auth/ForgetPassword'));
const ResetPassword = React.lazy(() => import('../../admin/components/auth/ResetPassword'));
const Dashboard = React.lazy(() => import('../../admin/components/dashboard/Dashboard'));

const admin = [
    {
        title: 'Login',
        path: Routes.web.admin.login,
        exact: true,
        component: Login,
        private: false
    },
    {
        title: 'Forget Password',
        path: Routes.web.admin.forgetPassword,
        exact: true,
        component: ForgetPassword,
        private: false
    },
    {
        title: 'Reset Password',
        path: Routes.web.admin.resetPassword,
        exact: true,
        component: ResetPassword,
        private: false
    },
    {
        title: 'Dashboard',
        path: Routes.web.admin.dashboard,
        exact: true,
        component: Dashboard,
        private: true
    },
]



const ReactRoutes = {
    admin
};

export default ReactRoutes;
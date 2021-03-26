
import React from 'react';
import Routes from './Routes';
const Login = React.lazy(() => import('../../admin/components/auth/Login'));
const ForgetPassword = React.lazy(() => import('../../admin/components/auth/ForgetPassword'));
const ResetPassword = React.lazy(() => import('../../admin/components/auth/ResetPassword'));
const Dashboard = React.lazy(() => import('../../admin/components/dashboard/Dashboard'));
const Settings = React.lazy(() => import('../../admin/components/settings/Settings'));
const PortfolioConfig = React.lazy(() => import('../../admin/components/portfolioConfig/PortfolioConfig'));
const PortfolioAbout = React.lazy(() => import('../../admin/components/portfolioAbout/PortfolioAbout'));

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
    {
        title: 'Settings',
        path: Routes.web.admin.settings,
        exact: true,
        component: Settings,
        private: true
    },
    {
        title: 'PortfolioConfig',
        path: Routes.web.admin.portfolioConfig,
        exact: true,
        component: PortfolioConfig,
        private: true
    },
    {
        title: 'PortfolioAbout',
        path: Routes.web.admin.portfolioAbout,
        exact: true,
        component: PortfolioAbout,
        private: true
    },
]



const ReactRoutes = {
    admin
};

export default ReactRoutes;

import React from 'react';
import loadable from '@loadable/component';
import Routes from './Routes';
const Login = loadable(() => import('../../admin/components/auth/Login'));
const ForgetPassword = loadable(() => import('../../admin/components/auth/ForgetPassword'));
const ResetPassword = loadable(() => import('../../admin/components/auth/ResetPassword'));
const Dashboard = loadable(() => import('../../admin/components/dashboard/Dashboard'));
const Settings = loadable(() => import('../../admin/components/settings/Settings'));
const PortfolioConfig = loadable(() => import('../../admin/components/portfolioConfig/PortfolioConfig'));
const PortfolioAbout = loadable(() => import('../../admin/components/portfolioAbout/PortfolioAbout'));

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
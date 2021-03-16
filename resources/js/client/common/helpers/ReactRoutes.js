
import React from 'react';
import Routes from './Routes';
const Login = React.lazy(() => import('../../admin/components/auth/Login'));

const admin = [
    {
        title: 'Login',
        path: Routes.web.admin.login,
        exact: true,
        component: Login,
        private: false
    },
]



const ReactRoutes = {
    admin
};

export default ReactRoutes;
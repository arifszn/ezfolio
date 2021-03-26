import Utils from "./Utils";

const web = {
    admin: {
        login: '/admin/login',
        forgetPassword: '/admin/forget-password',
        resetPassword: '/admin/reset-password/:token',
        notFound: '/admin/not-found',
        dashboard: '/admin/dashboard',
        settings: '/admin/settings',
        portfolioConfig: '/admin/portfolio/config',
        portfolioAbout: '/admin/portfolio/about',
    },
    user: {
        home: '/',
        notFound: '/not-found',
    }
};

const api = {
    admin: {
        login: Utils.backend+'/api/'+Utils.apiVersion+'/admin/login',
        forgetPassword: Utils.backend+'/api/'+Utils.apiVersion+'/admin/forget-password',
        resetPassword: Utils.backend+'/api/'+Utils.apiVersion+'/admin/reset-password',
    },
};

const Routes = {
    web,
    api
};

export default Routes;
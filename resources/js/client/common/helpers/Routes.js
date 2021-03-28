import Utils from "./Utils";

const web = {
    admin: {
        admin: '/admin',
        login: '/admin/login',
        forgetPassword: '/admin/forget-password',
        resetPassword: '/admin/reset-password/:token',
        notFound: '/admin/not-found',
        dashboard: '/admin/dashboard',
        settings: '/admin/settings',
        portfolioConfig: '/admin/portfolio/config',
        portfolioAbout: '/admin/portfolio/about',
        systemLogs: '/admin/system-logs',
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
        refreshToken: Utils.backend+'/api/'+Utils.apiVersion+'/admin/refresh-token',
        loginCredentials: Utils.backend+'/api/'+Utils.apiVersion+'/admin/login-credentials',
        settings: Utils.backend+'/api/'+Utils.apiVersion+'/admin/settings',
    },
};

const Routes = {
    web,
    api
};

export default Routes;
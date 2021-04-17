import Utils from "./Utils";

const web = {
    admin: {
        admin: '/admin',
        login: '/admin/login',
        logout: '/admin/logout',
        forgetPassword: '/admin/forget-password',
        resetPassword: '/admin/reset-password/:token',
        notFound: '/admin/not-found',
        dashboard: '/admin/dashboard',
        settings: '/admin/settings',
        portfolioConfig: '/admin/portfolio/config',
        portfolioAbout: '/admin/portfolio/about',
        portfolioEducation: '/admin/portfolio/education',
        portfolioSkills: '/admin/portfolio/skills',
        portfolioExperiences: '/admin/portfolio/experiences',
        portfolioProjects: '/admin/portfolio/projects',
        portfolioServices: '/admin/portfolio/services',
        visitors: '/admin/visitors',
        messages: '/admin/messages',
        systemLogs: '/admin/system-logs',
    },
    frontend: {
        home: '/',
        optimize: '/optimize',
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
        logo: Utils.backend+'/api/'+Utils.apiVersion+'/admin/logo',
        favicon: Utils.backend+'/api/'+Utils.apiVersion+'/admin/favicon',
        mailSettings: Utils.backend+'/api/'+Utils.apiVersion+'/admin/mail-settings',
        portfolioConfigs: Utils.backend+'/api/'+Utils.apiVersion+'/admin/portfolio-configs',
        seo: Utils.backend+'/api/'+Utils.apiVersion+'/admin/seo',
        about: Utils.backend+'/api/'+Utils.apiVersion+'/admin/about',
        avatar: Utils.backend+'/api/'+Utils.apiVersion+'/admin/avatar',
        cv: Utils.backend+'/api/'+Utils.apiVersion+'/admin/cv',
        cover: Utils.backend+'/api/'+Utils.apiVersion+'/admin/cover',
        education: Utils.backend+'/api/'+Utils.apiVersion+'/admin/education',
        skills: Utils.backend+'/api/'+Utils.apiVersion+'/admin/skills',
        experiences: Utils.backend+'/api/'+Utils.apiVersion+'/admin/experiences',
        projects: Utils.backend+'/api/'+Utils.apiVersion+'/admin/projects',
        services: Utils.backend+'/api/'+Utils.apiVersion+'/admin/services',
        visitorsStats: Utils.backend+'/api/'+Utils.apiVersion+'/admin/visitors/stats',
        messages: Utils.backend+'/api/'+Utils.apiVersion+'/admin/messages',
        stats: Utils.backend+'/api/'+Utils.apiVersion+'/admin/stats',
    },
    frontend: {
        projects: Utils.backend+'/api/'+Utils.apiVersion+'/frontend/projects',
    },
};

const Routes = {
    web,
    api
};

export default Routes;
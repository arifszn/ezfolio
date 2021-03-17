import React, { Suspense, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { ConfigProvider } from 'antd';
import enUSIntl from 'antd/lib/locale/en_US';
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import Routes from '../../common/helpers/Routes';
import SuspenseErrorBoundary from '../../common/components/SuspenseErrorBoundary';
import ErrorBoundaryFallbackUI from '../../common/components/ErrorBoundaryFallbackUI';
import LazyLoading from '../../common/components/lazyLoading/LazyLoading';
import '../../common/assets/css/app.scss';
import ReactRoutes from '../../common/helpers/ReactRoutes';
import { changeAntdTheme } from 'mini-dynamic-antd-theme';
import { Provider, useDispatch } from 'react-redux';
import { initializeGlobalState } from '../redux/ActionCreators';
import ConfigureStore from '../redux/ConfigureStore';
import PrivateRoute from '../components/PrivateRoute';
import Layout from '../components/layout/Layout';
const NotFound = React.lazy(() => import('../components/Notfound'));

const store = ConfigureStore();

/**
 * Remove an element by showing fade out effect
 * 
 * @param Element el 
 * @param int speed in millisecond
 */
const fadeoutAndRemoveElement = (el, speed) => {
    var seconds = speed/1000;
    el.style.transition = "opacity "+seconds+"s ease";

    el.style.opacity = 0;
    setTimeout(function() {
        el.parentNode.removeChild(el);
    }, speed);
}

/**
 * public routes
 */
const publicRoutes = () => {
    return ReactRoutes.admin.filter(route => route.private === false).map((route, index) => (
        <Route key={index} exact={route.exact} path={route.path}>
            <route.component/>
        </Route>
    ));
}

/**
 * private routes
 */
const privateRoutes = () => {
    return ReactRoutes.admin.filter(route => route.private === true).map((route, index) => (
        <Route key={index} exact={route.exact} path={route.path}>
            <route.component/>
        </Route>
    ));
}

/**
 * Root component
 */
const App = () => {
    const dispatch = useDispatch();
    // eslint-disable-next-line no-undef
    let mySettings = settings;
    
    dispatch(initializeGlobalState({
        token: null,
        activeMenu: null,
        activeSubMenu: null,
        accentColor: mySettings.accentColor,
        navbarBG: mySettings.navbarBG,
        navbarColor: mySettings.navbarColor,
        sidebarBG: mySettings.sidebarBG,
        sidebarColor: mySettings.sidebarColor,
        shortMenu: mySettings.shortMenu,
        siteName: mySettings.siteName,
        logo: mySettings.logo,
        favicon: mySettings.favicon,
        avatar: mySettings.avatar,
        demoMode: mySettings.demoMode,
        cover: mySettings.cover,
    }));

    useEffect(()=> {
        //remove preloader
        let preloader = document.getElementById("szn-preloader");
        
        if (preloader) {
             fadeoutAndRemoveElement(preloader, 1000);
        }

        changeAntdTheme(mySettings.accentColor);
    }, []);

	return (
		<React.Fragment>
            <SuspenseErrorBoundary fallback={<ErrorBoundaryFallbackUI/>}>
                <Suspense fallback={<LazyLoading/>}>
                    <ConfigProvider locale={enUSIntl}>
                        <BrowserRouter>
                            <Switch>
                                
                                {/* public routes */}
                                {publicRoutes()}

                                {/* private routes */}
                                <PrivateRoute>
                                    <Layout>
                                        <Switch>
                                            {privateRoutes()}

                                            <Route>
                                                <NotFound/>
                                            </Route>
                                        </Switch>
                                    </Layout>
                                </PrivateRoute>

                                {/* 404 route */}
                                <Route>
                                    <NotFound/>
                                </Route>
                                <Route path={Routes.web.admin.notFound}>
                                    <NotFound/>
                                </Route>
                                
                            </Switch>
                        </BrowserRouter>
                    </ConfigProvider>
                </Suspense>
            </SuspenseErrorBoundary>
        </React.Fragment>
	);
}

if (document.getElementById('react-root')) {
    ReactDOM.render(
        <React.StrictMode>
            <Provider store={store}>
                <App/>
            </Provider>
        </React.StrictMode>,
        document.getElementById('react-root')
    );
}
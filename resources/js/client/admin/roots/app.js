import React, { useEffect } from 'react';
import ReactDOM from 'react-dom';
import { ConfigProvider } from 'antd';
import enUSIntl from 'antd/lib/locale/en_US';
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import Routes from '../../common/helpers/Routes';
import SuspenseErrorBoundary from '../../common/components/SuspenseErrorBoundary';
import {ErrorBoundaryFallbackUI} from '../../common/components/ErrorBoundaryFallbackUI';
import '../../common/assets/css/app.css';
import ReactRoutes from '../../common/helpers/ReactRoutes';
import { Provider, useDispatch } from 'react-redux';
import { initializeGlobalState } from '../redux/ActionCreators';
import ConfigureStore from '../redux/ConfigureStore';
import PrivateRoute from '../components/PrivateRoute';
import loadable from '@loadable/component';
import ZLayout from '../components/layout/ZLayout';
import LazyLoadingFallbackUi from '../../common/components/lazyLoadingFallbackUi/LazyLoadingFallbackUi';
import { setupInterceptors } from '../../common/helpers/HTTP';
import Utils from '../../common/helpers/Utils';
const NotFound = loadable(() => import('../components/Notfound'));

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
            <route.component fallback={<LazyLoadingFallbackUi/>}/>
        </Route>
    ));
}

/**
 * private routes
 */
const privateRoutes = () => {
    return ReactRoutes.admin.filter(route => route.private === true).map((route, index) => (
        <Route key={index} exact={route.exact} path={route.path}>
            <route.component fallback={<LazyLoadingFallbackUi spinner={true}/>}/>
        </Route>
    ));
}

/**
 * Root component
 */
const App = () => {
    const dispatch = useDispatch();

    /**
     * Below settings are coming from 
     * resources/views/admin/app.blade.php
     * line 17. Ideally to get the settings, a request should be made to api.
     * 
     * But to keep it simple, we are passing the values in a constant which is
     * already declared in that mentioned file.
     */
    // eslint-disable-next-line no-undef
    let mySettings = settings;

    const apiToken = (localStorage.getItem("apiToken") !== 'undefined' && localStorage.getItem("apiToken") !== null) ? localStorage.getItem("apiToken") : null;
    
    dispatch(initializeGlobalState({
        apiToken: apiToken,
        accentColor: mySettings.accentColor,
        shortMenu: mySettings.shortMenu,
        menuLayout: mySettings.menuLayout,
        menuColor: mySettings.menuColor,
        navColor: mySettings.navColor,
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

        Utils.changeAccentColor(mySettings.accentColor);

        setupInterceptors(store);
    }, []);

	return (
		<React.Fragment>
            <SuspenseErrorBoundary fallback={<ErrorBoundaryFallbackUI/>}>
                <ConfigProvider locale={enUSIntl}>
                    <BrowserRouter>
                        <Switch>
                            
                            {/* public routes */}
                            {publicRoutes()}

                            {/* private routes */}
                            <PrivateRoute>
                                <ZLayout>
                                    <Switch>
                                        {privateRoutes()}
                                        <Route>
                                            <NotFound/>
                                        </Route>
                                    </Switch>
                                </ZLayout>
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
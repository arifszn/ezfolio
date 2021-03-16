import React, { Suspense, useEffect } from 'react';
import ReactDOM from 'react-dom';
import LazyLoading from '../../common/components/lazyLoading/LazyLoading';
import ErrorBoundaryFallbackUI from '../../common/ErrorBoundaryFallbackUI';
import SuspenseErrorBoundary from '../../common/SuspenseErrorBoundary';
import { ConfigProvider } from 'antd';
import enUSIntl from 'antd/lib/locale/en_US';
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import NotFound from '../../user/components/notFound/Notfound';
import ReactRoutes from '../../common/helpers/ReactRoutes';
import Routes from '../../common/helpers/Routes';
import './../../common/assets/css/app.scss';

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
 * Root component
 */
const App = () => {
    useEffect(()=> {
        //remove preloader
        let preloader = document.getElementById("szn-preloader");
        
        if (preloader) {
             fadeoutAndRemoveElement(preloader, 1000);
        }
    }, []);

	return (
		<React.Fragment>
            Hello
        </React.Fragment>
	);
}

if (document.getElementById('react-root')) {
    ReactDOM.render(
        <React.StrictMode>
            <App/>
        </React.StrictMode>,
        document.getElementById('react-root')
    );
}
import { useEffect } from 'react';
import NProgress from 'nprogress';
import './LazyLoading.css';

/**
 * Lazy loading suspense fallback ui
 * 
 * This component provides a progress bar on top
 * when navigating routes.
 */
const LazyLoading = () => {
    NProgress.configure({ showSpinner: false });

    useEffect(() => {
        NProgress.start();

        return () => {
            NProgress.done();
        };
    });

    return '';
};

export default LazyLoading;
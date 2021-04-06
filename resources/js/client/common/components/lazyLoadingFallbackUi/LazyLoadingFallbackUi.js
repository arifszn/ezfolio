import React, { useEffect } from 'react';
import NProgress from 'nprogress';
import './LazyLoadingFallbackUi.css';
import { Spin } from 'antd';
import styled from 'styled-components';
import PropTypes from 'prop-types';

const Wrapper = styled.div`
    display: flex;
    position: absolute;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
`;

/**
 * Lazy loading suspense fallback ui
 * 
 * This component provides a progress bar on top
 * when navigating routes.
 */
const LazyLoadingFallbackUi = ({spinner = false}) => {
    NProgress.configure({ showSpinner: false });

    useEffect(() => {
        NProgress.start();

        return () => {
            NProgress.done();
        };
    });

    return (
        <React.Fragment>
            {
                spinner ? (
                    <Wrapper>
                        <Spin size="large" delay={500}/>
                    </Wrapper>
                ) : ''
            }
        </React.Fragment>
    );
};

LazyLoadingFallbackUi.propTypes = {
    spinner: PropTypes.bool,
} 

export default LazyLoadingFallbackUi;
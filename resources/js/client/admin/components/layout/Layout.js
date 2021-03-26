import React from 'react';
import ProLayout from '@ant-design/pro-layout';
import { HomeOutlined, ControlOutlined, ExperimentOutlined } from '@ant-design/icons';
import { useHistory, useLocation } from 'react-router-dom';
import { useSelector } from 'react-redux';
import Routes from '../../../common/helpers/Routes';
import PropTypes from 'prop-types';
import Utils from '../../../common/helpers/Utils';

const Layout = ({ children }) => {
    const location = useLocation();
    const globalState = useSelector(state => state.globalState);
    const favicon = globalState.favicon;
    const siteName = globalState.siteName;
    let history = useHistory();

    const defaultProps = {
        title: siteName,
        navTheme: 'light',
        // layout: 'top',
        fixedHeader: true,
        logo: `${Utils.backend}/${favicon}`,
        route: {
            routes: [
                {
                    path: Routes.web.admin.dashboard,
                    name: 'Dashboard',
                    icon: <HomeOutlined />,
                },
                {
                    path: Routes.web.admin.settings,
                    name: 'Settings',
                    icon: <ControlOutlined />,
                },
                {
                    path: 'portfolio',
                    name: 'Portfolio',
                    icon: <ExperimentOutlined />,
                    routes: [
                    
                        {
                            path: Routes.web.admin.portfolioConfig,
                            name: 'Portfolio Config',
                        },
                        {
                            path: Routes.web.admin.portfolioAbout,
                            name: 'Portfolio About',
                        },
                    ],
                },
            ],
        },
    };

    const navigateToPath = (path) => {
        history.push(path);
    }
    
    return (
        <React.Fragment>
            <div
                style={{
                    height: '100vh',
                }}
            >
                <ProLayout
                    {...defaultProps}
                    location={location}
                    fixSiderbar
                    onMenuHeaderClick={() => navigateToPath(Routes.web.admin.dashboard)}
                    menuItemRender={(item, dom) => (
                        <a
                            onClick={(e) => {
                                e.preventDefault();
                                navigateToPath(item.path);
                            }}
                            href={item.path}
                        >
                            {dom}
                        </a>
                    )}
                    breadcrumbRender={() => ('')}
                    // rightContentRender={() => <RightContent/>}
                >
                    {children}
                    
                </ProLayout>
            </div>
        </React.Fragment>
    )
}

Layout.propTypes = {
    children: PropTypes.node,
}

export default Layout;
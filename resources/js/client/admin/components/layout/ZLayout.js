import React from 'react';
import ProLayout from '@ant-design/pro-layout';
import { useHistory, useLocation } from 'react-router-dom';
import { useSelector } from 'react-redux';
import Routes from '../../../common/helpers/Routes';
import PropTypes from 'prop-types';
import Utils from '../../../common/helpers/Utils';
import {
  HomeOutlined,
  FileTextOutlined,
  ExperimentOutlined,
  ControlOutlined,
} from '@ant-design/icons';
import { BackTop } from 'antd';

const ZLayout = ({ children }) => {
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
                    key: Routes.web.admin.dashboard,
                    name: 'Dashboard',
                    icon: <HomeOutlined />,
                },
                {
                    path: 'portfolio',
                    name: 'Portfolio',
                    icon: <ExperimentOutlined />,
                    routes: [
                        {
                            path: Routes.web.admin.portfolioConfig,
                            key: Routes.web.admin.portfolioConfig,
                            name: 'Config',
                        },
                        {
                            path: Routes.web.admin.portfolioAbout,
                            key: Routes.web.admin.portfolioAbout,
                            name: 'About',
                        },
                    ],
                },
                {
                    path: Routes.web.admin.systemLogs,
                    key: Routes.web.admin.systemLogs,
                    name: 'System Logs',
                    isExternalLink: true,
                    icon: <FileTextOutlined/>,
                },
                {
                    path: Routes.web.admin.settings,
                    key: Routes.web.admin.settings,
                    name: 'Settings',
                    icon: <ControlOutlined />,
                },
            ]
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
                                if (typeof item.isExternalLink !== 'undefined' && item.isExternalLink) {
                                    window.open(item.path);
                                } else {
                                    navigateToPath(item.path);
                                }
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
                    <BackTop />
                </ProLayout>
            </div>
        </React.Fragment>
    )
}

ZLayout.propTypes = {
    children: PropTypes.node,
}

export default ZLayout;
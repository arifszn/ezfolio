import React, { useEffect, useState } from 'react';
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
  PieChartOutlined,
  MailOutlined
} from '@ant-design/icons';
import { BackTop } from 'antd';
import NavContent from './NavContent';
import { useIsMobile } from '../../../common/hooks/IsMobile';

const ZLayout = ({ children }) => {
    const isMobile = useIsMobile();
    const location = useLocation();
    const globalState = useSelector(state => state.globalState);
    const logo = globalState.logo;
    const siteName = globalState.siteName;
    const menuColor = globalState.menuColor;
    const navColor = globalState.navColor;
    const layout = globalState.menuLayout;
    const [collapsed, setCollapsed] = useState(false);
    let history = useHistory();

    useEffect(() => {
        !isMobile && setCollapsed(globalState.shortMenu);
    }, [globalState.shortMenu])

    const defaultProps = {
        title: siteName,
        navTheme: menuColor,
        headerTheme: navColor,
        layout: !isMobile ? layout : 'mix',
        fixedHeader: true,
        collapsed: collapsed,
        onCollapse: (_collapsed) => {
            setCollapsed(_collapsed)
        },
        logo: `${Utils.backend}/${logo}`,
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
                        {
                            path: Routes.web.admin.portfolioSkills,
                            key: Routes.web.admin.portfolioSkills,
                            name: 'Skills',
                        },
                        {
                            path: Routes.web.admin.portfolioEducation,
                            key: Routes.web.admin.portfolioEducation,
                            name: 'Education',
                        },
                        {
                            path: Routes.web.admin.portfolioExperiences,
                            key: Routes.web.admin.portfolioExperiences,
                            name: 'Experience',
                        },
                        {
                            path: Routes.web.admin.portfolioProjects,
                            key: Routes.web.admin.portfolioProjects,
                            name: 'Project',
                        },
                        {
                            path: Routes.web.admin.portfolioServices,
                            key: Routes.web.admin.portfolioServices,
                            name: 'Service',
                        },
                    ],
                },
                {
                    path: Routes.web.admin.visitors,
                    key: Routes.web.admin.visitors,
                    name: 'Visitor',
                    icon: <PieChartOutlined/>,
                },
                {
                    path: Routes.web.admin.messages,
                    key: Routes.web.admin.messages,
                    name: 'Message',
                    icon: <MailOutlined/>,
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
                    rightContentRender={() => <NavContent/>}
                    breadcrumbRender={() => ('')}
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

export default React.memo(ZLayout);
import React, { useEffect, useState } from 'react';
import styled from 'styled-components';
import ZTabs from '../ZTabs';
import Icon from '@ant-design/icons';
import { AiOutlineSetting } from 'react-icons/ai';
import { IoColorPaletteOutline } from 'react-icons/io5';
import { RiMailSettingsLine } from 'react-icons/ri';
import { GrConfigure, GrTemplate } from 'react-icons/gr';
import Template from './Template';
import PageWrapper from '../layout/PageWrapper';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { Spin } from 'antd';

const PortfolioConfig = () => {
    const [templateConfig, setTemplateConfig] = useState(null);
    const [componentLoading, setComponentLoading] = useState(true);

    useEffect(() => {
        loadConfig(true);
    }, [])

    const loadConfig = (_componentLoadingState = false) => {
        setComponentLoading(_componentLoadingState);
        
        HTTP.get(Routes.api.admin.portfolioConfigs)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                setTemplateConfig(response.data.payload.template);
            })
        })
        .catch(error => {
            Utils.handleException(error);
        }).finally(() => {
            setComponentLoading(false);
        });
    }

    const tabs = [
        {
            key: 'template-settings',
            title: <React.Fragment><Icon component={GrTemplate}/> Template Settings</React.Fragment>,
            content: <Template config={templateConfig}/>
        },
    ]

    return (
        <React.Fragment>
            <PageWrapper loading={componentLoading}>
                <ZTabs tabs={tabs}/>
            </PageWrapper>
        </React.Fragment>
    )
}

export default PortfolioConfig;
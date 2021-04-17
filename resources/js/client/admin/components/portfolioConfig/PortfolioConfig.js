import React, { useEffect, useState } from 'react';
import ZTabs from '../ZTabs';
import Icon from '@ant-design/icons';
import Basic from './Basic';
import PageWrapper from '../layout/PageWrapper';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { BiMenu, BiCodeAlt } from 'react-icons/bi';
import { AiOutlineControl } from 'react-icons/ai';
import Visibility from './Visibility';
import CustomScript from './CustomScript';
import { Spin } from 'antd';

const PortfolioConfig = () => {
    const [config, setConfig] = useState(null);
    const [componentLoading, setComponentLoading] = useState(false);
    const [triggerLoadConfig, setTriggerLoadConfig] = useState(0);

    useEffect(() => {
        if (triggerLoadConfig !== 0) {
            loadConfig(true);
        }
    }, [triggerLoadConfig])

    const loadConfig = (_componentLoadingState = false) => {
        setComponentLoading(_componentLoadingState);
        
        HTTP.get(Routes.api.admin.portfolioConfigs)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                setConfig(response.data.payload);
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
            key: 'basic',
            title: <React.Fragment><Icon component={AiOutlineControl}/> Basic</React.Fragment>,
            content: <Basic mountedCallBack={() => setTriggerLoadConfig(triggerLoadConfig + 1)} config={config}/>
        },
        {
            key: 'visibility',
            title: <React.Fragment><Icon component={BiMenu}/> Visibility</React.Fragment>,
            content: <Visibility mountedCallBack={() => setTriggerLoadConfig(triggerLoadConfig + 1)} config={config}/>
        },
        {
            key: 'custom-script',
            title: <React.Fragment><Icon component={BiCodeAlt}/> Custom Script</React.Fragment>,
            content: <CustomScript mountedCallBack={() => setTriggerLoadConfig(triggerLoadConfig + 1)} config={config}/>
        },
    ]

    return (
        <React.Fragment>
            <Spin delay={500} size="large" spinning={componentLoading}>
                <PageWrapper noPadding>
                    <ZTabs tabs={tabs}/>
                </PageWrapper>
            </Spin>
        </React.Fragment>
    )
}

export default PortfolioConfig;
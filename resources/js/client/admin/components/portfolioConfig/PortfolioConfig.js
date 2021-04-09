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

const PortfolioConfig = () => {
    const [config, setConfig] = useState(null);
    const [componentLoading, setComponentLoading] = useState(true);

    useEffect(() => {
        loadConfig(true);
    }, [])

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
            content: <Basic config={config}/>
        },
        {
            key: 'visibility',
            title: <React.Fragment><Icon component={BiMenu}/> Visibility</React.Fragment>,
            content: <Visibility config={config}/>
        },
        {
            key: 'custom-script',
            title: <React.Fragment><Icon component={BiCodeAlt}/> Custom Script</React.Fragment>,
            content: <CustomScript config={config}/>
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
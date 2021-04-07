import React, { useEffect, useState } from 'react';
import ZTabs from '../ZTabs';
import Icon from '@ant-design/icons';
import Basic from './Basic';
import PageWrapper from '../layout/PageWrapper';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import { BiMenu } from 'react-icons/bi';
import { AiOutlineControl } from 'react-icons/ai';
import Menu from './Menu';

const PortfolioConfig = () => {
    const [basicConfig, setBasicConfig] = useState(null);
    const [menuConfig, setMenuConfig] = useState(null);
    const [componentLoading, setComponentLoading] = useState(true);

    useEffect(() => {
        loadConfig(true);
    }, [])

    const loadConfig = (_componentLoadingState = false) => {
        setComponentLoading(_componentLoadingState);
        
        HTTP.get(Routes.api.admin.portfolioConfigs)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                setBasicConfig(response.data.payload);
                setMenuConfig(response.data.payload.menu);
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
            content: <Basic config={basicConfig}/>
        },
        {
            key: 'menu',
            title: <React.Fragment><Icon component={BiMenu}/> Menu</React.Fragment>,
            content: <Menu config={menuConfig}/>
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